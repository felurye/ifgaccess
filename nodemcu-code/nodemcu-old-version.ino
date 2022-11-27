#include <LiquidCrystal_I2C.h>
#include <Wire.h>
#include <SPI.h>
#include <FS.h>
#include <MFRC522.h>
#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>

/* Ligações do MFRC522 para o ESP8266 (ESP-12)
  RST     = GPIO3 / SDA(SS) = GPIO4
  MOSI    = GPIO13 / MISO    = GPIO12
  SCK     = GPIO14 / GND     = GND
  3.3V    = 3.3V */

#define SS_PIN 4  // SDA-PIN MFRC522 - RFID - SPI - Modulo GPIO4 - D2
#define RST_PIN 3 // RST-PIN MFRC522 - RFID - SPI - Modulo GPIO02 - D4

LiquidCrystal_I2C lcd(0x27, 16, 2);
MFRC522 mfrc522(SS_PIN, RST_PIN); // Cria acesso MFRC522

WiFiClient wifiClient;
String serverAddress = "http://192.168.1.11";
String ssid = "Betelgeuse_2G";
String password = "1q2w3e4r*";

void setup() {
  Serial.begin(115200);
  delay(250);
  Serial.println("Iniciando....");

  SPI.begin();        // Inicia a serial SPI para o leitor
  mfrc522.PCD_Init(); // Inicia o leitor RFID
  lcd.begin();
  lcd.home();
  lcd.print("Hello, IFGAccess!");

  pinMode(0, OUTPUT); // Saída que aciona a abertura da porta

  SPIFFS.begin(); // Inicia o sistema de arquivos
  if (!SPIFFS.exists("/f.txt")) {
    Serial.println("Não localizou o arquivo. Formatando ...");
    SPIFFS.format();
  }

  WiFi.begin(ssid, password);
  int tentativas = 0;
  while ((WiFi.status() != WL_CONNECTED) && tentativas++ < 20) {
    delay(500);
    Serial.print(".");
  }

  if (WiFi.status() == WL_CONNECTED)
    Serial.println("WiFi conectado");
}

void atualiza_local() {

  HTTPClient http;
  Serial.print("[HTTP] Início...\n");

  // Configura URL
  http.begin(wifiClient, serverAddress);
  if (http.GET() == HTTP_CODE_OK) { // Conectou, atualiza o arquivo
    String remoto = http.getString();
    Serial.printf("Remoto: %s\n", remoto.c_str());
    File f = SPIFFS.open("/f.txt", "r");
    String local = f.readString();
    Serial.printf("Local: %s\n", local.c_str());
    f.close();
    if (remoto != local) {
      Serial.println("Atualizando ...");
      File f = SPIFFS.open("/f.txt", "w");
      f.print(remoto);
    }
  }
}

char *read_RFID(char *buffer) {
  if (mfrc522.PICC_IsNewCardPresent() && mfrc522.PICC_ReadCardSerial()) {
    buffer = dump_byte_array(mfrc522.uid.uidByte, mfrc522.uid.size, buffer);
    // Mostra o código da tag em hexadecimal
    Serial.printf("Tag UID:%s\n", buffer);
    return buffer;
  }
  else
    return NULL;
}

// Função para converter código RFID para char [] em hexadecimal
char *dump_byte_array(byte *buffer, byte bufferSize, char *result) {
  for (byte i = 0; i < bufferSize; i++) {
    char num[3];
    itoa(buffer[i], num, 16);
    if (buffer[i] <= 0xF)
      strcat(result, "0");
    strcat(result, num);
  }
  return result;
}

unsigned long anterior_tarefa = 0;

void loop() {
  lcd.setCursor(0,0);
  lcd.print("Aproxime a tag do leitor");
  
  char code_RFID[8] = "";
  int httpCode = 0;

  if ((millis() - anterior_tarefa > 100000) && (WiFi.status() == WL_CONNECTED)) {
    atualiza_local();
    anterior_tarefa = millis();
  }

  if (read_RFID(code_RFID)) {
    lcd.setCursor(0,0);
    lcd.print("Verificando...");
    if (WiFi.status() == WL_CONNECTED) {
      
      HTTPClient http;
      Serial.print("[HTTP] Início...\n");

      http.begin(wifiClient, serverAddress + "/app/functions/teste.php");
      http.addHeader("content-type", "application/x-www-form-urlencoded");

      String body = "class=405S&tag=" + String(code_RFID);
      httpCode = http.POST(body);

      if (httpCode > 0) {
        Serial.printf("[HTTP] POST... código: %d\n", httpCode);

        if (httpCode == HTTP_CODE_OK) { // Conectou, atualiza o arquivo
          String payload = http.getString();
          Serial.println(payload);
          if (payload == "OK") { // Localizou, libera o acesso
            lcd.setCursor(0,0);
            lcd.print("Acesso liberado");
            digitalWrite(0, HIGH);
            delay(1000); // Tempo suficiente para acionar o acesso
            digitalWrite(0, LOW);
          }
        }
      }
      else
        lcd.setCursor(0,0);
        lcd.print("Cadastre a tag: " + String(code_RFID));
        Serial.printf("[HTTP] POST... código: %d\n", httpCode);
      http.end();
    }

    if ((WiFi.status() != WL_CONNECTED) || (httpCode != HTTP_CODE_OK)) {
      // Houve erro na consulta ao banco
      File f = SPIFFS.open("/f.txt", "r");
      String local = f.readString();
      f.close();
      if (local.indexOf(code_RFID) > 0) { // Localizou no arquivo
        lcd.setCursor(0,0);
        lcd.print("Acesso liberado");
        Serial.printf("Acesso local liberado: %s\n", local.c_str());
        digitalWrite(0, HIGH);
        delay(100); // Tempo suficiente para acionar o acesso
        digitalWrite(0, LOW);
      }
    }
  }
}
