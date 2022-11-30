#include <ESP8266WebServer.h>
#include <ESP8266HTTPClient.h>
#include <SPI.h>
#include <MFRC522.h>
#include <LiquidCrystal_I2C.h>
#include <Wire.h> // Biblioteca utilizada para fazer a comunicação com o I2C

#define SS_PIN D4
#define RST_PIN D3
#define ON_Board_LED 2

MFRC522 mfrc522(SS_PIN, RST_PIN);
LiquidCrystal_I2C lcd(0x27, 16, 2);
WiFiClient wifiClient;
ESP8266WebServer server(80);

const char *ssid = "Betelgeuse_2G";
const char *password = "1q2w3e4r*";

int readsuccess;
byte readcard[4];
char str[32] = "";
String StrUID;

void setup()
{
  Serial.begin(115200);
  SPI.begin();
  mfrc522.PCD_Init();

  delay(500);

  WiFi.begin(ssid, password);
  Serial.println("");

  pinMode(ON_Board_LED, OUTPUT);

  Serial.print("Connecting");
  while (WiFi.status() != WL_CONNECTED)
  {
    Serial.print(".");
    delay(100);
  }

  lcd.begin();
  lcd.home();
  lcd.print("Aproxime a tag");
  lcd.setCursor(0, 1);
  lcd.print("do leitor!");

  Serial.println("");
  Serial.print("Conectado a rede com sucesso: ");
  Serial.println(ssid);
  Serial.print("Endereço IP: ");
  Serial.println(WiFi.localIP());

  Serial.println("Por favor, aproxime a tag ou cartão do leitor!");
  Serial.println("");
}

void loop()
{
  readsuccess = getid();

  if (readsuccess)
  {
    HTTPClient http;
    String tagResult, postData;
    tagResult = StrUID;

    postData = "tagResult=" + tagResult + "&room=S405";
    Serial.print("TAG RECEBIDA: " + tagResult + "\n");

    http.begin(wifiClient, "http://192.168.1.19/func/createAccess.php");
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");

    int httpCode = http.POST(postData);
    String response = http.getString();

    Serial.print("Status Code: ");
    Serial.println(httpCode);
    Serial.println("Response: " + response);

    validateAccess(response, tagResult);

    http.end();
    delay(1000);

    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("Aproxime a tag");
    lcd.setCursor(0, 1);
    lcd.print("do leitor!");
  }
}

void validateAccess(String response, String tag)
{
  lcd.clear();
  lcd.setCursor(0, 0);
  if (response == "Checkin")
  {
    lcd.print("Acesso liberado!");
  }
  else if (response == "Checkout")
  {
    lcd.print("Checkout!!!");
  }
  else if (response == "Wait for another user to checkout")
  {
    lcd.print("Aguarde checkout!");
  }
  else
  {
    lcd.print("Acesso negado!");
  }
  lcd.setCursor(0, 1);
  lcd.print("TAG: " + tag);
  delay(2000);
}

int getid()
{
  if (!mfrc522.PICC_IsNewCardPresent())
  {
    return 0;
  }
  if (!mfrc522.PICC_ReadCardSerial())
  {
    return 0;
  }

  for (int i = 0; i < 4; i++)
  {
    readcard[i] = mfrc522.uid.uidByte[i];
    array_to_string(readcard, 4, str);
    StrUID = str;
  }
  mfrc522.PICC_HaltA();
  return 1;
}

void array_to_string(byte array[], unsigned int len, char buffer[])
{
  for (unsigned int i = 0; i < len; i++)
  {
    byte nib1 = (array[i] >> 4) & 0x0F;
    byte nib2 = (array[i] >> 0) & 0x0F;
    buffer[i * 2 + 0] = nib1 < 0xA ? '0' + nib1 : 'A' + nib1 - 0xA;
    buffer[i * 2 + 1] = nib2 < 0xA ? '0' + nib2 : 'A' + nib2 - 0xA;
  }
  buffer[len * 2] = '\0';
}