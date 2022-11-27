## Preparação do Ambiente

### Pré requisitos

- Docker
- Docker Compose
- IDE/Editor de preferência
- Servidor Web
    - Nginx
- Interpretador PHP
    - PHP-FPM
- Banco de dados
    - MySQL
- Composer PHP
- Comandos Artisan


### Configuração do Ambiente de desenvolvimento PHP

#### Porque usar Docker Compose?

O Docker Compose é uma ferramenta que facilita a operação e ambientes em contêineres, baseado em configurações definidas em um arquivo YAML.

Neste arquivo, definimos os serviços que irão compor o ambiente da aplicação, bem como os volumes e networks que serão compartilhados por esses serviços.

- Possibilita a criação de ambientes complexos com um simples arquivo.yml
- Pode rodar múltiplos ambientes ao mesmo tempo
- Preserva volumes
- Suporte a variáveis para build e customização

Exemplo de arquivo YAML:

```yml

version: '3.7'
services:
    web:
        image: nginx
        ports:
            - "8000:80"
```

Este arquivo docker-compose.yml cria um serviço chamado "web" baseado na imagem do Nginx, e redireciona requisições na porta local 8000 para a porta 80 no container.


Comando para subir a imagem configurada no arquivo docker-compose.yml

> docker-compose up

Comandos:

- Controlar o ambiente
    - up, down, stop, start

- Monitorar / Auditar
    - ps, logs, top, kill

- Executar comandos bash nos contêineres
    - exec service_name command
    - docker-compose exec app bash


##### Contêineres e Imagens

- Nginx
    - Imagem do DockerHub
- MySQL
    - Imagem do DockerHub
- App (PHP-FPM)
    - Imagem baseada em Dockerfile


##### Volumes e Redes Compartilhadas

- Volumes
    - Aquivos da aplicação
        - host >> app, nginx
    - Arquivo de conf. Nginx
        - host >> nginx
    - Dump do banco de dados
        - host >> mysql

- Redes
    - host >> app, nginx, mysql



#### Anotações

- dockerfile
    Configura as imagens a serem instaladas

- docker-compose.yaml
    Configurações do ambiente.
 
> ln -s public html
__________________

[Tutorial detalhado](https://www.digitalocean.com/community/tutorials/how-to-install-and-set-up-laravel-with-docker-compose-on-ubuntu-20-04)