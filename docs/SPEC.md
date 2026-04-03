# IFGAccess — Especificação do Sistema

> Documento de referência para implementação baseada em especificação (Spec Driven Development).
> Define **o que** o sistema deve fazer: regras de negócio, contratos de dados, invariantes e critérios de aceite. Não prescreve tecnologia ou estrutura de código.

---

## Índice

1. [Conceitos do Domínio](#1-conceitos-do-domínio)
2. [Modelo de Dados](#2-modelo-de-dados)
3. [Regras de Negócio](#3-regras-de-negócio)
4. [Funcionalidades](#4-funcionalidades)
   - 4.1 [Cadastro de Usuário](#41-cadastro-de-usuário)
   - 4.2 [Edição de Usuário](#42-edição-de-usuário)
   - 4.3 [Exclusão de Usuário](#43-exclusão-de-usuário)
   - 4.4 [Listagem de Usuários](#44-listagem-de-usuários)
   - 4.5 [Controle de Acesso](#45-controle-de-acesso)
   - 4.6 [Consulta de Tag em Tempo Real](#46-consulta-de-tag-em-tempo-real)
   - 4.7 [Histórico de Acessos](#47-histórico-de-acessos)
5. [Contratos de API](#5-contratos-de-api)
6. [Comportamento do Dispositivo Embarcado](#6-comportamento-do-dispositivo-embarcado)
7. [Infraestrutura e Configuração](#7-infraestrutura-e-configuração)
8. [Critérios de Aceite Globais](#8-critérios-de-aceite-globais)

---

## 1. Conceitos do Domínio

| Termo | Definição |
|-------|-----------|
| **Tag** | Identificador único de 8 caracteres hexadecimais (maiúsculos) gerado a partir do UID de um cartão/tag RFID de 13,56 MHz. Exemplo: `A1B2C3D4`. |
| **Usuário** | Pessoa cadastrada no sistema, identificada por uma tag RFID. Possui nome, matrícula, e-mail e telefone. |
| **Sala** | Ambiente físico controlado pelo sistema. Identificada por um código alfanumérico de até 10 caracteres (ex: `S405`). |
| **Checkin** | Evento que registra a entrada de um usuário em uma sala. Ocorre quando o usuário aproxima sua tag do leitor e não possui checkin pendente. |
| **Checkout** | Evento que registra a saída de um usuário de uma sala. Ocorre quando o usuário aproxima sua tag do leitor e possui um checkin pendente (sem checkout). |
| **Sessão de Acesso** | Par checkin/checkout de um usuário em uma sala. A sessão está **aberta** enquanto o checkout for nulo; está **fechada** após o registro do checkout. |
| **Sessão Pendente** | Sessão de acesso com checkin registrado e checkout nulo. Indica que o usuário está atualmente na sala. |

---

## 2. Modelo de Dados

### 2.1 Entidade: `users` (Usuários)

| Campo | Tipo | Nulável | Único | Regras |
|-------|------|---------|-------|--------|
| `id` | INTEGER | Não | Sim (PK) | Gerado automaticamente. |
| `tag` | VARCHAR(8) | Não | **Sim** | Exatamente 8 caracteres hexadecimais maiúsculos (`[0-9A-F]{8}`). Imutável após o cadastro. |
| `name` | VARCHAR(80) | Não | Não | Mínimo 1 caractere. Máximo 80 caracteres. |
| `enrollment` | VARCHAR(14) | Não | Não | Mínimo 1 caractere. Máximo 14 caracteres. |
| `email` | VARCHAR(45) | Sim | Não | Formato de e-mail válido quando informado. Máximo 45 caracteres. |
| `phone` | VARCHAR(11) | Sim | Não | Somente dígitos. Mínimo 10 dígitos, máximo 11 dígitos quando informado. |

### 2.2 Entidade: `access` (Acessos)

| Campo | Tipo | Nulável | Regras |
|-------|------|---------|--------|
| `id` | INTEGER | Não | Gerado automaticamente. |
| `user_id` | INTEGER | Não | Chave estrangeira referenciando `users.id`. |
| `room` | VARCHAR(10) | Não | Código da sala. Mínimo 1 caractere. Máximo 10 caracteres. |
| `checkin` | TIMESTAMP | Não | Data e hora de entrada. Fuso horário: `America/Sao_Paulo`. Formato: `YYYY-MM-DD HH:MM:SS`. |
| `checkout` | TIMESTAMP | **Sim** | Data e hora de saída. Nulo enquanto o usuário estiver na sala. Deve ser posterior ao `checkin` da mesma sessão. |

### 2.3 Invariantes do Modelo

- **INV-01:** Um usuário só pode ter **no máximo uma sessão pendente** por vez (checkin sem checkout).
- **INV-02:** Uma sala só pode ter **no máximo um usuário com sessão pendente** por vez.
- **INV-03:** O campo `tag` de um usuário não pode ser alterado após o cadastro.
- **INV-04:** Um registro de acesso não pode ser excluído ou alterado após ser criado, exceto para registrar o `checkout`.
- **INV-05:** O `checkout` de uma sessão deve ser sempre posterior ao `checkin` da mesma sessão.

---

## 3. Regras de Negócio

### RN-01 — Unicidade da Tag
Cada tag RFID deve estar vinculada a no máximo um usuário. O sistema deve rejeitar o cadastro de um usuário com uma tag já registrada.

### RN-02 — Imutabilidade da Tag
A tag vinculada a um usuário não pode ser alterada após o cadastro. O campo `tag` deve ser ignorado em operações de atualização.

### RN-03 — Checkin por Aproximação
Quando um usuário aproxima sua tag do leitor e **não possui sessão pendente**, o sistema deve registrar um checkin com o timestamp atual no fuso horário `America/Sao_Paulo`.

### RN-04 — Checkout por Aproximação
Quando um usuário aproxima sua tag do leitor e **possui sessão pendente**, o sistema deve registrar o checkout na sessão mais recente, atualizando o campo `checkout` com o timestamp atual.

### RN-05 — Acesso Exclusivo por Sala
Apenas **um usuário por vez** pode ter sessão pendente em uma mesma sala. Se um usuário tentar fazer checkin em uma sala que já possui outro usuário com sessão pendente, o acesso deve ser negado e o sistema deve retornar aviso de espera.

### RN-06 — Tag Não Cadastrada
Quando o sistema recebe uma tag que não corresponde a nenhum usuário cadastrado, o acesso deve ser negado. Nenhum registro de acesso é criado.

### RN-07 — Exclusão de Usuário
A exclusão de um usuário deve ser precedida de confirmação explícita. Registros de acesso associados ao usuário devem ser preservados (histórico de auditoria).

### RN-08 — Preenchimento Automático de Tag no Cadastro
Na tela de cadastro de usuário, o campo de tag deve ser preenchido automaticamente com o valor lido pelo leitor RFID, sem necessidade de digitação manual.

### RN-09 — Fuso Horário
Todos os timestamps registrados pelo sistema devem usar o fuso horário `America/Sao_Paulo`.

### RN-10 — Ordenação do Histórico de Acessos
O histórico de acessos deve ser exibido em ordem decrescente de checkin (acesso mais recente primeiro).

### RN-11 — Ordenação da Lista de Usuários
A lista de usuários deve ser exibida em ordem crescente de nome.

---

## 4. Funcionalidades

### 4.1 Cadastro de Usuário

**Pré-condições:**
- O sistema está operacional e conectado ao banco de dados.

**Entradas:**

| Campo | Obrigatório | Regras de Validação |
|-------|-------------|---------------------|
| `tag` | Sim | Exatamente 8 caracteres hexadecimais (`[0-9A-F]{8}`). Deve ser única no sistema (RN-01). Preenchida automaticamente pelo leitor (RN-08). |
| `name` | Sim | 1–80 caracteres. |
| `enrollment` | Sim | 1–14 caracteres. |
| `email` | Não | Formato de e-mail válido. Máximo 45 caracteres. |
| `phone` | Não | Somente dígitos. 10 ou 11 dígitos. |

**Fluxo Principal:**
1. O operador acessa a tela de cadastro.
2. O leitor RFID detecta uma tag e o campo `tag` é preenchido automaticamente.
3. O operador preenche os demais campos e confirma.
4. O sistema valida os dados conforme as regras da tabela acima.
5. O sistema verifica se a tag já está cadastrada (RN-01).
6. O registro do usuário é criado no banco de dados.
7. O sistema exibe a lista de usuários atualizada.

**Fluxos Alternativos:**
- **[FA-01] Tag já cadastrada:** O sistema rejeita o cadastro e informa que a tag já está vinculada a outro usuário.
- **[FA-02] Campos obrigatórios ausentes:** O sistema rejeita o cadastro e indica os campos faltantes.
- **[FA-03] Tag com formato inválido:** O sistema rejeita o cadastro e informa o formato esperado.

**Critérios de Aceite:**
- [ ] Um usuário cadastrado com sucesso aparece na lista de usuários.
- [ ] Não é possível cadastrar dois usuários com a mesma tag.
- [ ] O campo `tag` não aceita valores fora do padrão `[0-9A-F]{8}`.
- [ ] Os campos `name` e `enrollment` são obrigatórios; o cadastro é rejeitado quando ausentes.

---

### 4.2 Edição de Usuário

**Pré-condições:**
- O usuário existe no sistema.

**Entradas:**

| Campo | Editável | Regras de Validação |
|-------|----------|---------------------|
| `tag` | **Não** | Ignorado na atualização (RN-02). |
| `name` | Sim | 1–80 caracteres. Obrigatório. |
| `enrollment` | Sim | 1–14 caracteres. Obrigatório. |
| `email` | Sim | Formato de e-mail válido. Máximo 45 caracteres. |
| `phone` | Sim | Somente dígitos. 10 ou 11 dígitos. |

**Fluxo Principal:**
1. O operador acessa a tela de edição com o identificador do usuário.
2. O sistema exibe o formulário com os dados atuais do usuário.
3. O campo `tag` é exibido como somente leitura e não pode ser alterado.
4. O operador altera os campos desejados e confirma.
5. O sistema valida os dados.
6. O registro do usuário é atualizado no banco de dados.
7. O sistema exibe a lista de usuários atualizada.

**Critérios de Aceite:**
- [ ] A tag do usuário não pode ser alterada via edição.
- [ ] Os campos `name` e `enrollment` são obrigatórios; a atualização é rejeitada quando ausentes.
- [ ] As alterações são persistidas e refletidas na lista de usuários.

---

### 4.3 Exclusão de Usuário

**Pré-condições:**
- O usuário existe no sistema.

**Fluxo Principal:**
1. O operador solicita a exclusão de um usuário.
2. O sistema exibe uma tela de confirmação.
3. O operador confirma a exclusão.
4. O registro do usuário é removido do banco de dados.
5. Os registros de acesso associados são **preservados** (RN-07).
6. O sistema exibe a lista de usuários atualizada.

**Critérios de Aceite:**
- [ ] A exclusão só ocorre após confirmação explícita do operador.
- [ ] O usuário excluído não aparece mais na lista de usuários.
- [ ] Os registros de acesso do usuário excluído permanecem no histórico.

---

### 4.4 Listagem de Usuários

**Saída:** Lista de todos os usuários cadastrados, ordenada por nome em ordem crescente (RN-11), contendo: nome, tag, matrícula, e-mail, telefone e ações (editar, excluir).

**Critérios de Aceite:**
- [ ] Todos os usuários cadastrados são exibidos.
- [ ] A lista está ordenada alfabeticamente por nome.
- [ ] Cada linha contém links funcionais para edição e exclusão.

---

### 4.5 Controle de Acesso

Esta é a funcionalidade central do sistema. É acionada pelo dispositivo ESP8266 ao detectar a aproximação de uma tag RFID.

**Entrada:**

| Parâmetro | Tipo | Obrigatório | Regras |
|-----------|------|-------------|--------|
| `tagResult` | String | Sim | 8 caracteres hexadecimais (`[0-9A-F]{8}`). |
| `room` | String | Sim | Código da sala. Máximo 10 caracteres. |

**Árvore de Decisão:**

```
Recebeu tagResult e room?
├── NÃO → [ERRO] Requisição inválida
└── SIM
      │
      ▼
Existe usuário com tag = tagResult?
├── NÃO → Retorna "NOK" (RN-06)
└── SIM
      │
      ▼
Existe outro usuário com sessão pendente na mesma sala?
├── SIM → Retorna "Wait for another user to checkout" (RN-05)
└── NÃO
      │
      ▼
O usuário possui sessão pendente em qualquer sala?
├── NÃO → Registra checkin → Retorna "Checkin" (RN-03)
└── SIM → Registra checkout → Retorna "Checkout" (RN-04)
```

**Respostas:**

| Resposta | Condição | Ação no banco |
|----------|----------|---------------|
| `"Checkin"` | Tag cadastrada, sem sessão pendente, sala livre | Insere registro em `access` com `checkin = NOW()` |
| `"Checkout"` | Tag cadastrada, com sessão pendente | Atualiza o registro mais recente de `access` com `checkout = NOW()` |
| `"Wait for another user to checkout"` | Tag cadastrada, sala ocupada por outro usuário | Nenhuma |
| `"NOK"` | Tag não encontrada no banco | Nenhuma |

**Efeito Colateral:**
O valor da tag recebida deve ser disponibilizado para as telas de consulta e cadastro em tempo real (mecanismo de armazenamento temporário da última tag lida).

**Critérios de Aceite:**
- [ ] Tag não cadastrada retorna exatamente `"NOK"` e nenhum registro é criado.
- [ ] Tag cadastrada sem sessão pendente retorna `"Checkin"` e um registro é criado com `checkout = NULL`.
- [ ] A mesma tag aproximada novamente retorna `"Checkout"` e o `checkout` da sessão mais recente é preenchido.
- [ ] Uma tag diferente tentando fazer checkin em sala já ocupada retorna `"Wait for another user to checkout"` e nenhum registro é criado.
- [ ] Dois usuários distintos não podem ter sessão pendente na mesma sala simultaneamente.
- [ ] Um usuário não pode ter mais de uma sessão pendente.

---

### 4.6 Consulta de Tag em Tempo Real

**Comportamento:**
A tela de consulta deve refletir automaticamente os dados do usuário vinculado à **última tag lida** pelo sistema (qualquer operação que leia uma tag atualiza o estado).

**Saída para tag cadastrada:**

| Campo | Valor |
|-------|-------|
| Tag | Valor hexadecimal da tag |
| Nome | Nome do usuário |
| Matrícula | Matrícula do usuário |
| E-mail | E-mail do usuário |
| Telefone | Telefone do usuário |

**Saída para tag não cadastrada:** Mensagem informando que a tag não está cadastrada no sistema.

**Frequência de atualização:** A tela deve verificar se há uma nova tag lida em intervalos regulares (máximo 1 segundo entre verificações).

**Critérios de Aceite:**
- [ ] Ao aproximar uma tag cadastrada do leitor, os dados do usuário são exibidos em até 1 segundo.
- [ ] Ao aproximar uma tag não cadastrada, é exibida mensagem de tag não cadastrada.
- [ ] A tela é atualizada automaticamente sem necessidade de recarregar a página.

---

### 4.7 Histórico de Acessos

**Saída:** Lista de todos os registros de acesso, em ordem decrescente de checkin (RN-10), contendo: nome do usuário, matrícula, sala, data/hora de checkin, data/hora de checkout.

**Observações:**
- Sessões abertas (checkout nulo) devem ser exibidas com o campo checkout em branco ou com indicativo visual.
- O histórico inclui registros de usuários eventualmente excluídos do sistema (RN-07).

**Critérios de Aceite:**
- [ ] Todos os registros de acesso são exibidos, incluindo sessões abertas.
- [ ] A lista está ordenada pelo checkin mais recente primeiro.
- [ ] Cada registro exibe nome, matrícula, sala, checkin e checkout.

---

## 5. Contratos de API

Todos os endpoints são acessíveis via HTTP. A aplicação não possui autenticação de sessão.

### POST `/func/createAccess.php`

**Descrição:** Processa a leitura de uma tag RFID pelo dispositivo embarcado.

**Content-Type:** `application/x-www-form-urlencoded`

**Corpo da requisição:**

| Parâmetro | Tipo | Obrigatório |
|-----------|------|-------------|
| `tagResult` | String (8 hex chars) | Sim |
| `room` | String (max 10 chars) | Sim |

**Respostas:**

| Corpo | Condição |
|-------|----------|
| `Checkin` | Checkin registrado com sucesso |
| `Checkout` | Checkout registrado com sucesso |
| `Wait for another user to checkout` | Sala ocupada por outro usuário |
| `NOK` | Tag não cadastrada |

---

### GET `/readTagData.php?tag={tag}`

**Descrição:** Retorna os dados do usuário vinculado à tag informada.

**Parâmetro de query:**

| Parâmetro | Tipo | Obrigatório |
|-----------|------|-------------|
| `tag` | String (8 hex chars) | Sim |

**Respostas:**

| Condição | Comportamento |
|----------|---------------|
| Tag cadastrada | Retorna nome, matrícula, e-mail e telefone do usuário |
| Tag não cadastrada | Retorna mensagem de tag não cadastrada |

---

### POST `/func/createUser.php`

**Descrição:** Cria um novo usuário no sistema.

**Content-Type:** `application/x-www-form-urlencoded`

**Corpo da requisição:**

| Parâmetro | Tipo | Obrigatório | Restrições |
|-----------|------|-------------|------------|
| `tag` | String | Sim | `[0-9A-F]{8}`, única |
| `name` | String | Sim | 1–80 chars |
| `enrollment` | String | Sim | 1–14 chars |
| `email` | String | Não | e-mail válido, max 45 chars |
| `phone` | String | Não | somente dígitos, 10–11 chars |

**Resposta:** Redireciona para `/users.php` em caso de sucesso.

---

### POST `/func/editUser.php?id={id}`

**Descrição:** Atualiza os dados de um usuário existente.

**Content-Type:** `application/x-www-form-urlencoded`

**Parâmetro de query:**

| Parâmetro | Tipo | Obrigatório |
|-----------|------|-------------|
| `id` | Integer | Sim |

**Corpo da requisição:**

| Parâmetro | Tipo | Obrigatório | Restrições |
|-----------|------|-------------|------------|
| `name` | String | Sim | 1–80 chars |
| `enrollment` | String | Sim | 1–14 chars |
| `email` | String | Não | e-mail válido, max 45 chars |
| `phone` | String | Não | somente dígitos, 10–11 chars |

> O campo `tag` é ignorado nesta operação (RN-02).

**Resposta:** Redireciona para `/users.php` em caso de sucesso.

---

### POST `/deleteUser.php`

**Descrição:** Exclui um usuário após confirmação.

**Content-Type:** `application/x-www-form-urlencoded`

**Corpo da requisição:**

| Parâmetro | Tipo | Obrigatório |
|-----------|------|-------------|
| `id` | Integer | Sim |

**Resposta:** Redireciona para `/users.php` em caso de sucesso.

---

## 6. Comportamento do Dispositivo Embarcado

### 6.1 Ciclo de Operação

```
INÍCIO
  │
  ▼
Inicializa leitor RFID, display LCD, conecta ao Wi-Fi
  │
  ▼
Exibe no LCD: "Aproxime a tag / do leitor!"
  │
  ▼
┌─────────────────────────────────────────┐
│  LOOP                                   │
│                                         │
│  Nova tag detectada?                    │
│  ├── NÃO → aguarda e repete             │
│  └── SIM                                │
│        │                                │
│        ▼                                │
│  Lê UID (4 bytes) → converte para       │
│  string hexadecimal de 8 chars          │
│        │                                │
│        ▼                                │
│  POST /func/createAccess.php            │
│  tagResult={TAG}&room={SALA}            │
│        │                                │
│        ▼                                │
│  Exibe resposta no LCD por 2 segundos   │
│        │                                │
│        ▼                                │
│  Limpa LCD → exibe mensagem de espera   │
│  Repete loop                            │
└─────────────────────────────────────────┘
```

### 6.2 Mapeamento de Respostas para o Display LCD

| Resposta do servidor | Linha 1 do LCD | Linha 2 do LCD |
|----------------------|----------------|----------------|
| `"Checkin"` | `Acesso liberado!` | `TAG: {valor}` |
| `"Checkout"` | `Checkout!!!` | `TAG: {valor}` |
| `"Wait for another user to checkout"` | `Aguarde checkout!` | `TAG: {valor}` |
| Qualquer outro valor | `Acesso negado!` | `TAG: {valor}` |

### 6.3 Parâmetros de Configuração do Firmware

Os seguintes parâmetros devem ser configurados antes de gravar o firmware:

| Parâmetro | Descrição | Localização no código |
|-----------|-----------|----------------------|
| `ssid` | Nome da rede Wi-Fi | Constante no início do arquivo |
| `password` | Senha da rede Wi-Fi | Constante no início do arquivo |
| `room` | Identificador da sala monitorada | Variável `postData` no loop principal |
| URL do servidor | Endereço IP e caminho do endpoint | Chamada `http.begin()` no loop principal |

### 6.4 Especificação de Hardware

| Componente | Interface | Pinos (NodeMCU) |
|------------|-----------|-----------------|
| MFRC522 (RFID) | SPI | SS=D4, RST=D3, MOSI=D7, MISO=D6, SCK=D5 |
| Display LCD 16x2 | I2C (endereço `0x27`) | SDA=D2, SCL=D1 |

---

## 7. Infraestrutura e Configuração

### 7.1 Serviços Requeridos

| Serviço | Requisito |
|---------|-----------|
| Servidor Web | Suporte a PHP 7.4+ com extensões `pdo` e `pdo_mysql` |
| Banco de Dados | MySQL 5.7+ ou MariaDB 10+ |
| Rede | Servidor Web e dispositivo ESP8266 devem estar na mesma rede local |

### 7.2 Configuração do Banco de Dados

| Parâmetro | Valor padrão |
|-----------|-------------|
| Host | `mysql` (nome do serviço Docker) |
| Banco | `ifgaccess` |
| Usuário | `ifgaccess` |
| Charset | `utf8` |

### 7.3 Fuso Horário da Aplicação

A aplicação deve operar no fuso horário `America/Sao_Paulo`. Todos os timestamps registrados no banco de dados devem refletir esse fuso horário.

---

## 8. Critérios de Aceite Globais

Os critérios abaixo devem ser satisfeitos pelo sistema como um todo.

### Integridade dos Dados
- [ ] Não existe mais de um usuário com a mesma tag no banco.
- [ ] Não existe mais de uma sessão pendente para o mesmo usuário.
- [ ] Não existe mais de uma sessão pendente para a mesma sala.
- [ ] Todo `checkout` registrado é posterior ao `checkin` da mesma sessão.

### Comportamento do Controle de Acesso
- [ ] A sequência tag→checkin→mesma tag→checkout funciona corretamente.
- [ ] Uma segunda tag não consegue fazer checkin enquanto outra tiver sessão pendente na mesma sala.
- [ ] Uma tag desconhecida nunca gera registro de acesso.

### Interface Web
- [ ] O campo de tag é preenchido automaticamente na tela de cadastro ao aproximar uma tag do leitor.
- [ ] A tela de consulta reflete a última tag lida sem necessidade de interação do usuário.
- [ ] A exclusão de um usuário requer confirmação e não remove o histórico de acessos.

### Dispositivo Embarcado
- [ ] O display LCD exibe a mensagem correta para cada resposta do servidor.
- [ ] O dispositivo retorna ao estado de espera após cada leitura.
- [ ] A comunicação com o servidor ocorre via HTTP POST com os parâmetros `tagResult` e `room`.
