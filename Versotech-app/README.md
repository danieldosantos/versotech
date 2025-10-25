# Versotech - Processamento de Produtos e Preços

Este projeto implementa o teste técnico para implantador de sistemas descrito no enunciado. Ele utiliza Laravel 12, PHP 8.2 e PostgreSQL para ingerir dados brutos de produtos e preços, normalizá-los através de views SQL e disponibilizar o resultado por meio de APIs e de uma interface web simples.

## Visão Geral

- **Tabelas de origem**: `produtos_base` e `precos_base` replicam fielmente as colunas e dados fornecidos no teste.
- **Views de processamento**: `vw_produtos_processados` e `vw_precos_processados` higienizam textos, convertem números, normalizam datas heterogêneas e filtram registros ativos.
- **Tabelas de destino**: `produto_insercao` e `preco_insercao` recebem os dados já tratados.
- **APIs**: endpoints REST permitem acionar o processamento e consultar os produtos com preços.
- **Frontend**: página com botões de execução e tabela responsiva para visualizar o resultado.

## Requisitos

- PHP 8.2+
- Composer
- PostgreSQL 12+
- Node 18+ (apenas se desejar reconstruir os assets via Vite)

## Instalação

```bash
cp .env.example .env
composer install
php artisan key:generate
```

Configure as credenciais do PostgreSQL no arquivo `.env` para as variáveis `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME` e `DB_PASSWORD`.

## Banco de Dados

Execute as migrations e a carga inicial:

```bash
php artisan migrate
php artisan db:seed
```

O diretório [`database/sql`](database/sql/base_schema.sql) contém um script SQL equivalente para criação e popularização das tabelas de origem, caso deseje carregar os dados diretamente no banco.

## Views de Processamento

As migrations criam duas views principais:

- `vw_produtos_processados`: normaliza códigos, títulos, textos e converte medidas (peso em quilogramas e dimensões em centímetros). Também padroniza datas com múltiplos formatos.
- `vw_precos_processados`: garante valores numéricos válidos para preço, desconto, acréscimo e promoção, além de converter datas textuais e considerar apenas registros com status ativo.

Os endpoints de processamento populam as tabelas de destino a partir dessas views sempre que executados.

## APIs Disponíveis

| Método | Rota                       | Descrição                                         |
|--------|---------------------------|---------------------------------------------------|
| POST   | `/api/processar-produtos` | Limpa a tabela `produto_insercao` e repovoa com os dados tratados de produtos. |
| POST   | `/api/processar-precos`   | Limpa a tabela `preco_insercao` e repovoa com os dados tratados de preços.    |
| GET    | `/api/produtos-com-precos`| Retorna a listagem consolidada de produtos com informações de preço.          |

As rotas retornam JSON com mensagens de status e, quando aplicável, a quantidade de registros afetados.

## Interface Web

A rota `/` entrega a view `resources/views/dashboard.blade.php`, que disponibiliza:

- Botões para acionar o processamento de produtos e preços.
- Botão para atualizar a listagem.
- Tabela responsiva exibindo dados tratados, incluindo valores monetários e descontos formatados.

A página utiliza `fetch` para consumir as APIs e apresenta mensagens de feedback sobre cada ação.

## Testes

O projeto mantém a suíte padrão do Laravel. Utilize `php artisan test` para executá-la.

---

Qualquer ajuste adicional (ex.: publicação em produção, autenticação ou paginação) pode ser implementado sobre esta base funcional.
