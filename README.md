# Versotech

Este repositório abriga a aplicação Laravel que vive dentro da pasta [`Versotech-app/`](Versotech-app/). O objetivo deste README é ser didático: cada etapa explica **qual comando executar**, **por quê** e **em qual diretório você deve estar** para que tudo funcione sem surpresas.

## Pré-requisitos

Instale os softwares abaixo antes de começar:

- PHP 8.2 ou superior
- Composer
- Node.js 18 ou superior (inclui o NPM)
- Banco de dados compatível com Laravel (MySQL, PostgreSQL etc.)

> **Dica:** Se estiver usando Docker ou ferramentas como Laravel Sail, adapte os comandos conforme o provedor escolhido.

> **Nota sobre PostgreSQL:** O banco de dados PostgreSQL está configurado para rodar no Docker. Para iniciá-lo, execute:
> ```bash
> docker run --name versotech-postgres -e POSTGRES_PASSWORD=postgres -e POSTGRES_DB=versotech_app -p 5432:5432 -d postgres:15
> ```

## Visão geral das pastas

| Caminho               | Descrição                                                                                     |
|-----------------------|-------------------------------------------------------------------------------------------------|
| `versotech/`          | Raiz do repositório. Execute aqui apenas comandos de Git e tarefas que envolvam o repositório. |
| `versotech/README.md` | Este guia geral.                                                                               |
| `Versotech-app/`      | Projeto Laravel completo (código PHP, migrations, assets front-end gerenciados pelo Vite).     |

## Referência rápida: onde rodar cada comando?

| Situação                                      | Comando(s)                                           | Diretório correto            |
|-----------------------------------------------|------------------------------------------------------|------------------------------|
| Clonar o projeto                              | `git clone ...`                                      | Qualquer pasta               |
| Entrar no repositório                         | `cd versotech`                                       | Onde você clonou o repo      |
| Instalar dependências PHP                     | `composer install`                                   | `versotech/Versotech-app/`   |
| Instalar dependências JavaScript              | `npm install`                                        | `versotech/Versotech-app/`   |
| Copiar `.env` e gerar key                     | `cp .env.example .env`<br>`php artisan key:generate` | `versotech/Versotech-app/`   |
| Configurar banco (editar `.env`)              | Abrir arquivo `.env` em um editor                    | `versotech/Versotech-app/`   |
| Rodar migrations e seeders                    | `php artisan migrate --seed`                         | `versotech/Versotech-app/`   |
| Subir servidor Laravel                        | `php artisan serve`                                  | `versotech/Versotech-app/`   |
| Rodar servidor Vite (front-end)               | `npm run dev`                                        | `versotech/Versotech-app/`   |
| Rodar build front-end                         | `npm run build`                                      | `versotech/Versotech-app/`   |
| Executar testes automatizados                 | `php artisan test`                                   | `versotech/Versotech-app/`   |
| Comandos Git                                  | `git status`, `git commit`, `git pull`, etc.         | `versotech/`                 |

## Passo a passo detalhado

### 1. Clonar o repositório

> **Diretório:** qualquer pasta na sua máquina (ex.: `~/Projetos`)

```bash
git clone <url-do-repositorio>
cd versotech
```

### 2. Acessar a aplicação Laravel

> **Diretório:** `versotech/`

Entre na pasta do projeto Laravel antes de instalar dependências:

```bash
cd Versotech-app
```

> A partir deste ponto, todos os comandos referenciados até o final da seção “Execução e testes” devem ser executados dentro de `Versotech-app/`.

### 3. Instalar dependências PHP

```bash
composer install
```

### 4. Instalar dependências JavaScript

```bash
npm install
```

### 5. Preparar variáveis de ambiente

```bash
cp .env.example .env
php artisan key:generate
```

Abra o arquivo `.env` e configure as credenciais do banco (`DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, etc.).

### 6. Migrar e popular o banco

```bash
php artisan migrate --seed
```

Se desejar apenas criar as tabelas sem dados iniciais, rode `php artisan migrate` e depois, se necessário, `php artisan db:seed`.

### 7. Execução do projeto

#### Backend (Laravel)

```bash
php artisan serve
```

O servidor ficará disponível em `http://127.0.0.1:8000`.

#### Front-end (assets com Vite)

Abra **outro terminal** na pasta `Versotech-app/` e execute:

```bash
npm run dev
```

Quando precisar gerar arquivos otimizados para produção, utilize `npm run build`.

### 8. Testes

Execute a suíte de testes do Laravel com:

```bash
php artisan test
```

Se existirem scripts adicionais no `package.json` (como `npm run lint`), eles também devem ser executados a partir de `Versotech-app/`.

## Dúvidas frequentes

- **Onde rodo comandos Git?** Sempre na raiz `versotech/`, porque ela contém o diretório `.git`.
- **Posso usar outro SGBD?** Sim, basta ajustar as variáveis de ambiente no `.env` para o driver desejado suportado pelo Laravel.
- **Onde ficam instruções avançadas?** Consulte [`Versotech-app/README.md`](Versotech-app/README.md) para detalhes sobre arquitetura, APIs e visão geral do teste técnico.

Bom desenvolvimento! 🚀
