# Versotech App

## Requisitos
- PHP 8.1+
- Composer
- PostgreSQL 13+

## Configuração
1) Entre na pasta do projeto `Versotech-app`.
2) Copie o arquivo de exemplo e gere a chave:
   - `cp .env.example .env`
   - `php artisan key:generate`
3) Configure o banco no `.env` (exemplo PostgreSQL local):
   ```env
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=versotech_app
   DB_USERNAME=postgres
   DB_PASSWORD=postgres
   ```
4) Instale as dependências: `composer install`
5) Rode as migrations (tabelas + views). Se quiser dados de exemplo, use `--seed`:
   - `php artisan migrate`
   - (opcional) `php artisan migrate --seed`

Banco via Docker (opcional):
```bash
docker run --name versotech-postgres \
  -e POSTGRES_PASSWORD=postgres -e POSTGRES_DB=versotech_app \
  -p 5432:5432 -d postgres:15
```

## Executando
- Servidor de desenvolvimento: `php artisan serve`
- Acesse: `http://127.0.0.1:8000`

## Frontend (dashboard)
Arquivo: `resources/views/dashboard.blade.php`

- Botões da interface:
  - `Processar Produtos`: executa o pipeline completo (processa produtos e preços) e depois atualiza a listagem.
  - `Listar Produtos com Preços`: atualiza a listagem sem reprocessar.



## Scripts úteis (opcionais)
- Recriar as views SQL de processamento: `php scripts/recreate_views.php`
- Processar via CLI: `php scripts/call_processor.php` (produtos), `php scripts/call_processor_prices.php` (preços)
- Conferir bases cruas: `php scripts/check_bases.php`
- Inspecionar datas da view de preços: `php scripts/debug_view_dates.php`

## Onde estão as coisas
- Controlador e lógica de carga: `app/Http/Controllers/DataProcessingController.php`
- Rotas da API: `routes/api.php`
- Views SQL de processamento: `database/migrations/2025_10_25_123236_create_processed_views.php`
- Migrações de tabelas base/destino: `database/migrations/*create_*_table.php`

