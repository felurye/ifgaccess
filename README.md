# IFGAccess

> Sistema Web para Controle de Acesso utilizando RFID

![GitHub](https://img.shields.io/github/license/felurye/ifgaccess?color=red)
![GitHub commit activity](https://img.shields.io/github/commit-activity/y/felurye/ifgaccess)
![GitHub last commit](https://img.shields.io/github/last-commit/felurye/ifgaccess)
![GitHub contributors](https://img.shields.io/github/contributors/felurye/ifgaccess)

## Sobre o projeto

IFGAccess é um sistema de controle de acesso que utiliza cartões e tags RFID para registrar entradas e saídas de usuários em salas. O gerenciamento é feito por uma interface Web, e o controle físico é realizado por um dispositivo de baixo custo instalado na porta do ambiente.

O projeto foi desenvolvido como trabalho acadêmico no Instituto Federal de Goiás (IFG). A monografia está disponível em [`docs/monografia.pdf`](docs/monografia.pdf).

## Funcionalidades

- Cadastro de usuários vinculados a uma tag RFID
- Registro automático de entrada e saída ao aproximar a tag do leitor
- Bloqueio de acesso simultâneo - apenas um usuário por vez em cada sala
- Histórico completo de acessos com data e hora
- Consulta em tempo real da tag lida

## Como rodar

**Pré-requisito:** [Docker](https://www.docker.com/get-started) instalado.

```bash
git clone https://github.com/felurye/ifgaccess.git
cd ifgaccess
docker-compose up --build -d
docker exec -i ifgaccess-db mysql -u ifgaccess -proot ifgaccess < codes/db_create_tables.sql
```

Acesse `http://localhost` no navegador.

> Para configurar o dispositivo físico (ESP8266), veja o [guia de instalação](https://github.com/felurye/ifgaccess/wiki/Clonar-e-rodar) na Wiki.

## Documentação

- [Wiki do projeto](https://github.com/felurye/ifgaccess/wiki) - funcionalidades, arquitetura e guia de uso
- [`docs/monografia.pdf`](docs/monografia.pdf) - monografia completa
- [`docs/`](docs/) - diagramas do sistema (abrir com [draw.io](https://app.diagrams.net/))

## Contribuindo

1. Faça um fork e crie uma branch: `git checkout -b feature/minha-feature`
2. Faça suas alterações e commit: `git commit -m "feat: descrição"`
3. Abra um Pull Request.

Para reportar bugs, abra uma [issue](https://github.com/felurye/ifgaccess/issues).

## Licença

Distribuído sob a licença [Apache 2.0](https://github.com/felurye/ifgaccess/blob/master/LICENSE).
