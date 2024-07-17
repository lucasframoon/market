# Market

Este é o repositório do projeto Market.

## Descrição

Este projeto é um sistema de mercado desenvolvido em PHP 8.2 no backend e React.js no frontend, utilizando PostgreSQL como banco de dados. O backend serve como uma API consumida pelo frontend.

## Como Executar

### Pré-requisitos

Antes de executar o projeto, certifique-se de que você tem os seguintes requisitos instalados em sua máquina:

- [PHP 8.2](https://www.php.net/downloads)
- [Composer](https://getcomposer.org/doc/00-intro.md)
- [PostgreSQL](https://www.postgresql.org/download/)
- [Node.js/npm](https://nodejs.org/en/download/package-manager/current)

### Configuração

1. Clone o repositório e navegue até a pasta do projeto:
    ```bash
    git clone https://github.com/lucasframoon/market.git
    cd market
    ```

2. Instale as dependências Node.js para hooks de Git(apenas para padronizar os commits):
    ```bash
    npm install
    ```

3. Navegue até a pasta `Api` e instale as dependências do PHP:
    ```bash
    cd Api
    composer install
    cd ..
    ```

4. Importe o banco de dados PostgreSQL a partir do dump localizado em `db/market_db.dump`:
    ```bash
    createdb -h localhost -p 5432 -U postgres market_db
    pg_restore -h localhost -p 5432 -U postgres -d market_db -v db/market_db.dump
    ```

5. Inicie o servidor PHP integrado:
    ```bash
    cd Api/public
    php -S localhost:8080
    ```

6. Navegue até a pasta `Front` e instale as dependências do frontend:
    ```bash
    cd front
    npm install
    ```

7. Inicie o servidor de desenvolvimento do React:
    ```bash
    npm start
    ```

Agora, o servidor PHP está rodando em [http://localhost:8080](http://localhost:8080) e o frontend está rodando em [http://localhost:3000](http://localhost:3000).

### Kanban
Usei o [git projects](https://github.com/users/lucasframoon/projects/3/views/1) para criar um kanban e organizar as tarefas.

