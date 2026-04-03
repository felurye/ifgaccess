# IFGAccess

> Sistema Web para Controle de Acesso utilizando RFID e o Microcontrolador ESP8266

![GitHub](https://img.shields.io/github/license/felurye/ifgaccess?color=red)
![GitHub commit activity](https://img.shields.io/github/commit-activity/y/felurye/ifgaccess)
![GitHub last commit](https://img.shields.io/github/last-commit/felurye/ifgaccess)
![GitHub contributors](https://img.shields.io/github/contributors/felurye/ifgaccess)

## Descrição

IFGAccess é um sistema Web de controle de acesso por meio da tecnologia de radiofrequência (RFID) e um dispositivo computacional de baixo custo (microcontrolador ESP8266 / NodeMCU). O sistema permite o cadastro e a verificação de tags RFID por meio de uma interface Web, registra automaticamente entradas e saídas de usuários em salas, e impede o acesso simultâneo de mais de um usuário ao mesmo ambiente.

O projeto foi desenvolvido como trabalho acadêmico no Instituto Federal de Goiás (IFG). A monografia completa está disponível em [`docs/monografia.pdf`](docs/monografia.pdf).

Para detalhes técnicos, funcionalidades e guia de uso, consulte a **[Wiki do projeto](https://github.com/felurye/ifgaccess/wiki)**.

## Como rodar

**Pré-requisito:** [Docker](https://www.docker.com/get-started) e [Docker Compose](https://docs.docker.com/compose/install/) instalados.

```bash
# 1. Clonar o repositório
git clone https://github.com/felurye/ifgaccess.git
cd ifgaccess

# 2. Subir os containers (PHP + MySQL)
docker-compose up --build -d

# 3. Criar as tabelas no banco de dados
docker exec -i ifgaccess-db mysql -u ifgaccess -proot ifgaccess < codes/db_create_tables.sql
```

Acesse `http://localhost` no navegador.

> Para configurar e gravar o firmware no ESP8266, veja [Clonar e rodar](https://github.com/felurye/ifgaccess/wiki/Clonar-e-rodar) na Wiki.

## Contribuindo

1. Faça um fork do repositório.
2. Crie uma branch para sua feature ou correção:
   ```bash
   git checkout -b feature/minha-feature
   ```
3. Realize suas alterações e faça o commit:
   ```bash
   git commit -m "feat: adiciona minha feature"
   ```
4. Envie para o seu fork:
   ```bash
   git push origin feature/minha-feature
   ```
5. Abra um Pull Request descrevendo as alterações.

Para reportar bugs ou sugerir melhorias, abra uma [issue](https://github.com/felurye/ifgaccess/issues).

## Documentação

- [Wiki do projeto](https://github.com/felurye/ifgaccess/wiki)
- [`docs/monografia.pdf`](docs/monografia.pdf) — monografia completa
- [`docs/*.drawio`](docs/) — diagramas editáveis (abrir com [draw.io](https://app.diagrams.net/))

## Licença

Distribuído sob a licença [Apache 2.0](https://github.com/felurye/ifgaccess/blob/master/LICENSE).
