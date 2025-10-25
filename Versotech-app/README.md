# 🚀 Versotech App

E aí! Esse projeto é uma aplicação em Laravel que faz o processamento de produtos e preços. Vou te explicar como fazer rodar na sua máquina.

## ⚡ Setup rápido

Primeiro, se liga no que você precisa ter instalado:

- PHP 8.2 ou mais recente
- Composer (pra instalar as dependências do Laravel)
- Node.js (pra rodar o frontend)
- PostgreSQL (ou Docker se preferir)

### 🐳 Se for usar Docker pro banco:

Cola esse comando no terminal:
```bash
docker run --name versotech-postgres -e POSTGRES_PASSWORD=postgres -e POSTGRES_DB=versotech_app -p 5432:5432 -d postgres:15
```

### 💻 Pra rodar o projeto:

1. Clona o repo:
```bash
git clone <url-do-repo>
cd Versotech-app
```

2. Instala as dependências:
```bash
composer install
npm install
```

3. Cria o arquivo .env e gera a chave:
```bash
cp .env.example .env
php artisan key:generate
```

4. Configura o banco no .env:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=versotech_app
DB_USERNAME=postgres
DB_PASSWORD=postgres
```

5. Cria as tabelas e coloca uns dados de exemplo:
```bash
php artisan migrate --seed
```

6. Roda a app:

No primeiro terminal:
```bash
php artisan serve
```

Em outro terminal:
```bash
npm run dev
```

Pronto! Só acessar http://localhost:8000 🎉

## � Como usar

Quando você abrir a app, vai ter 3 botões:

- **Processar Produtos**: Pega os produtos da base e trata os dados
- **Processar Preços**: Pega os preços e trata. Produtos sem preço aparecem como R$ 0,00
- **Listar Produtos com Preços**: Mostra só os produtos que têm preço maior que zero

## 🔧 Como funciona

A app tem:
- 2 tabelas base: `produtos_base` e `precos_base`
- Views SQL que limpam os dados: `vw_produtos_processados` e `vw_precos_processados`
- 2 tabelas de destino: `produto_insercao` e `preco_insercao`
- API REST pra processar e listar os produtos

Endpoints da API:
- POST `/api/processar-produtos`: Processa os produtos
- POST `/api/processar-precos`: Processa os preços
- GET `/api/produtos-com-precos`: Lista produtos com preço regular
- GET `/api/produtos-com-precos-inclusive`: Lista todos produtos (preço zero quando não tem)
- GET `/api/produtos`: Lista só produtos sem preço

## 🤔 Problemas comuns

### Erro de driver do Postgres
Se der erro de driver, é só descomentar essas linhas no php.ini:
```ini
extension=pdo_pgsql
extension=pgsql
```

### Erro no composer install
Tenta rodar:
```bash
composer update
```

### Página em branco
Verifica se você rodou:
```bash
npm install
npm run dev
```

## 🤝 Quer ajudar?

Tamo aceitando PR! Faz um fork, manda suas alterações e abre aquele PR maroto 😎

## 📞 Precisa de ajuda?

Qualquer dúvida, me chama! Tamo junto! �
