# Versotech

Este repositório contém a aplicação Laravel localizada em `Versotech-app/`. O README abaixo apresenta um passo a passo simples, mas completo, para preparar o ambiente e executar o projeto, destacando sempre em qual pasta cada comando deve ser rodado.

## Pré-requisitos
- PHP 8.2+
- Composer
- Node.js 18+
- NPM (instalado com o Node.js)
- Banco de dados compatível com Laravel (MySQL, PostgreSQL, etc.)

## Estrutura principal
- **`README.md`**: instruções gerais (este arquivo).
- **`Versotech-app/`**: código da aplicação Laravel (backend + assets front-end via Vite).

## Passo a passo

### 1. Clonar o repositório (qualquer pasta)
```bash
git clone <url-do-repositorio>
cd versotech
```

### 2. Instalar dependências PHP (dentro de `Versotech-app/`)
```bash
cd Versotech-app
composer install
```

### 3. Instalar dependências JS (ainda dentro de `Versotech-app/`)
```bash
npm install
```

### 4. Configurar variáveis de ambiente (dentro de `Versotech-app/`)
```bash
cp .env.example .env
php artisan key:generate
```
> Ajuste as variáveis de banco de dados e outros serviços diretamente no arquivo `.env`.

### 5. Executar migrações e seeders (dentro de `Versotech-app/`)
```bash
php artisan migrate --seed
```
> Se não quiser popular dados iniciais, remova `--seed`.

### 6. Subir o servidor Laravel (dentro de `Versotech-app/`)
```bash
php artisan serve
```
> O backend ficará disponível, por padrão, em `http://127.0.0.1:8000`.

### 7. Rodar o Vite para assets front-end (abra outro terminal em `Versotech-app/`)
```bash
npm run dev
```
> Para build de produção utilize `npm run build` (também em `Versotech-app/`).

## Testes
- **Testes PHP** (dentro de `Versotech-app/`):
  ```bash
  php artisan test
  ```
- **Lint/Format frontend**, conforme configurado em `package.json` (dentro de `Versotech-app/`), por exemplo:
  ```bash
  npm run lint
  ```

## Dicas adicionais
- Sempre que precisar instalar pacotes PHP ou atualizar o Artisan, faça isso a partir da pasta `Versotech-app/`.
- Para rodar comandos de versionamento (`git status`, `git commit`, etc.), utilize a raiz do repositório (`versotech/`).

Bom desenvolvimento! :rocket:
