# Versotech App

## Requisitos
- PHP 8.1 ou mais recente  
- Composer  
- PostgreSQL 13 ou mais  

## Como configurar
1. Entre na pasta do projeto:
   ```bash
   cd Versotech-app

Copie o arquivo .env.example e gere a chave:

cp .env.example .env
php artisan key:generate

Edite o arquivo .env e coloque os dados do banco, por exemplo:

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=versotech_app
DB_USERNAME=postgres
DB_PASSWORD=postgres

Instale as dependências:

composer install

Crie as tabelas do banco:

php artisan migrate

Banco com Docker 

docker run --name versotech-postgres \
  -e POSTGRES_PASSWORD=postgres -e POSTGRES_DB=versotech_app \
  -p 5432:5432 -d postgres:15

Rodar o sistema

php artisan serve