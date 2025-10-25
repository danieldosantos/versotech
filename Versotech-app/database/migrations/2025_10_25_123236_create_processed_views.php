<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement(<<<'SQL'
            CREATE OR REPLACE VIEW vw_produtos_processados AS
            WITH base AS (
                SELECT
                    p.prod_id,
                    UPPER(TRIM(p.prod_cod)) AS codigo,
                    INITCAP(REGEXP_REPLACE(TRIM(COALESCE(p.prod_nome, '')), '\\s+', ' ', 'g')) AS nome,
                    UPPER(TRIM(COALESCE(p.prod_cat, ''))) AS categoria,
                    UPPER(TRIM(COALESCE(p.prod_subcat, ''))) AS subcategoria,
                    TRIM(COALESCE(p.prod_desc, '')) AS descricao,
                    INITCAP(REGEXP_REPLACE(TRIM(COALESCE(p.prod_fab, '')), '\\s+', ' ', 'g')) AS fabricante,
                    UPPER(TRIM(COALESCE(p.prod_mod, ''))) AS modelo,
                    INITCAP(TRIM(COALESCE(p.prod_cor, ''))) AS cor,
                    REGEXP_REPLACE(LOWER(COALESCE(p.prod_peso, '')), '\\s+', '', 'g') AS peso_raw,
                    REGEXP_REPLACE(LOWER(COALESCE(p.prod_larg, '')), '\\s+', '', 'g') AS largura_raw,
                    REGEXP_REPLACE(LOWER(COALESCE(p.prod_alt, '')), '\\s+', '', 'g') AS altura_raw,
                    REGEXP_REPLACE(LOWER(COALESCE(p.prod_prof, '')), '\\s+', '', 'g') AS profundidade_raw,
                    UPPER(TRIM(COALESCE(p.prod_und, ''))) AS unidade,
                    COALESCE(p.prod_atv, false) AS ativo,
                    REGEXP_REPLACE(TRIM(COALESCE(p.prod_dt_cad, '')), '[./]', '-', 'g') AS data_bruta
                FROM produtos_base p
            )
            SELECT
                b.prod_id,
                b.codigo,
                b.nome,
                NULLIF(b.categoria, '') AS categoria,
                NULLIF(b.subcategoria, '') AS subcategoria,
                NULLIF(b.descricao, '') AS descricao,
                NULLIF(b.fabricante, '') AS fabricante,
                NULLIF(b.modelo, '') AS modelo,
                NULLIF(b.cor, '') AS cor,
                CASE
                    WHEN b.peso_raw LIKE '%kg' THEN REPLACE(REGEXP_REPLACE(b.peso_raw, '[^0-9,.-]', '', 'g'), ',', '.')::numeric
                    WHEN b.peso_raw LIKE '%g' THEN REPLACE(REGEXP_REPLACE(b.peso_raw, '[^0-9,.-]', '', 'g'), ',', '.')::numeric / 1000
                    WHEN b.peso_raw ~ '^[0-9,.-]+$' AND b.peso_raw <> '' THEN REPLACE(b.peso_raw, ',', '.')::numeric
                    ELSE NULL
                END AS peso_kg,
                CASE
                    WHEN b.largura_raw ~ '^[0-9,.-]+cm$' THEN REPLACE(REGEXP_REPLACE(b.largura_raw, 'cm$', '', ''), ',', '.')::numeric
                    WHEN b.largura_raw ~ '^[0-9,.-]+$' THEN REPLACE(b.largura_raw, ',', '.')::numeric
                    ELSE NULL
                END AS largura_cm,
                CASE
                    WHEN b.altura_raw ~ '^[0-9,.-]+cm$' THEN REPLACE(REGEXP_REPLACE(b.altura_raw, 'cm$', '', ''), ',', '.')::numeric
                    WHEN b.altura_raw ~ '^[0-9,.-]+$' THEN REPLACE(b.altura_raw, ',', '.')::numeric
                    ELSE NULL
                END AS altura_cm,
                CASE
                    WHEN b.profundidade_raw ~ '^[0-9,.-]+cm$' THEN REPLACE(REGEXP_REPLACE(b.profundidade_raw, 'cm$', '', ''), ',', '.')::numeric
                    WHEN b.profundidade_raw ~ '^[0-9,.-]+$' THEN REPLACE(b.profundidade_raw, ',', '.')::numeric
                    ELSE NULL
                END AS profundidade_cm,
                NULLIF(b.unidade, '') AS unidade,
                b.ativo,
                CASE
                    WHEN b.data_bruta ~ '^\\d{4}-\\d{2}-\\d{2}$' THEN TO_DATE(b.data_bruta, 'YYYY-MM-DD')
                    WHEN b.data_bruta ~ '^\\d{2}-\\d{2}-\\d{4}$' THEN TO_DATE(b.data_bruta, 'DD-MM-YYYY')
                    ELSE NULL
                END AS data_cadastro
            FROM base b;
        SQL);

        DB::statement(<<<'SQL'
            CREATE OR REPLACE VIEW vw_precos_processados AS
            WITH base AS (
                SELECT
                    pr.preco_id,
                    UPPER(TRIM(COALESCE(pr.prc_cod_prod, ''))) AS codigo_produto,
                    REGEXP_REPLACE(LOWER(COALESCE(pr.prc_valor, '')), '\\s+', '', 'g') AS valor_raw,
                    REGEXP_REPLACE(REGEXP_REPLACE(LOWER(COALESCE(pr.prc_valor, '')), '\\s+', '', 'g'), '[^0-9,.-]', '', 'g') AS valor_numeric_raw,
                    UPPER(TRIM(COALESCE(pr.prc_moeda, ''))) AS moeda,
                    REGEXP_REPLACE(LOWER(COALESCE(pr.prc_desc, '')), '\\s+', '', 'g') AS desconto_raw,
                    REGEXP_REPLACE(REGEXP_REPLACE(LOWER(COALESCE(pr.prc_desc, '')), '\\s+', '', 'g'), '[^0-9,.-]', '', 'g') AS desconto_numeric_raw,
                    REGEXP_REPLACE(LOWER(COALESCE(pr.prc_acres, '')), '\\s+', '', 'g') AS acrescimo_raw,
                    REGEXP_REPLACE(REGEXP_REPLACE(LOWER(COALESCE(pr.prc_acres, '')), '\\s+', '', 'g'), '[^0-9,.-]', '', 'g') AS acrescimo_numeric_raw,
                    REGEXP_REPLACE(LOWER(COALESCE(pr.prc_promo, '')), '\\s+', '', 'g') AS promo_raw,
                    REGEXP_REPLACE(REGEXP_REPLACE(LOWER(COALESCE(pr.prc_promo, '')), '\\s+', '', 'g'), '[^0-9,.-]', '', 'g') AS promo_numeric_raw,
                    REGEXP_REPLACE(TRIM(COALESCE(pr.prc_dt_ini_promo, '')), '[./]', '-', 'g') AS dt_ini_raw,
                    REGEXP_REPLACE(TRIM(COALESCE(pr.prc_dt_fim_promo, '')), '[./]', '-', 'g') AS dt_fim_raw,
                    REGEXP_REPLACE(TRIM(COALESCE(pr.prc_dt_atual, '')), '[./]', '-', 'g') AS dt_atual_raw,
                    TRIM(COALESCE(pr.prc_origem, '')) AS origem,
                    UPPER(TRIM(COALESCE(pr.prc_tipo_cli, ''))) AS tipo_cliente,
                    INITCAP(REGEXP_REPLACE(TRIM(COALESCE(pr.prc_vend_resp, '')), '\\s+', ' ', 'g')) AS vendedor_responsavel,
                    TRIM(COALESCE(pr.prc_obs, '')) AS observacao,
                    LOWER(TRIM(COALESCE(pr.prc_status, ''))) AS status
                FROM precos_base pr
            )
            SELECT
                b.preco_id,
                b.codigo_produto,
                CASE
                    WHEN b.valor_numeric_raw = '' THEN NULL
                    WHEN b.valor_numeric_raw LIKE '%,%' THEN REPLACE(REPLACE(b.valor_numeric_raw, '.', ''), ',', '.')::numeric
                    ELSE REPLACE(b.valor_numeric_raw, ',', '.')::numeric
                END AS valor,
                NULLIF(b.moeda, '') AS moeda,
                CASE
                    WHEN b.desconto_numeric_raw = '' THEN NULL
                    WHEN b.desconto_numeric_raw LIKE '%,%' THEN REPLACE(REPLACE(b.desconto_numeric_raw, '.', ''), ',', '.')::numeric / 100
                    ELSE REPLACE(b.desconto_numeric_raw, ',', '.')::numeric / 100
                END AS percentual_desconto,
                CASE
                    WHEN b.acrescimo_numeric_raw = '' THEN NULL
                    WHEN b.acrescimo_numeric_raw LIKE '%,%' THEN REPLACE(REPLACE(b.acrescimo_numeric_raw, '.', ''), ',', '.')::numeric / 100
                    ELSE REPLACE(b.acrescimo_numeric_raw, ',', '.')::numeric / 100
                END AS percentual_acrescimo,
                CASE
                    WHEN b.promo_numeric_raw = '' THEN NULL
                    WHEN b.promo_numeric_raw LIKE '%,%' THEN REPLACE(REPLACE(b.promo_numeric_raw, '.', ''), ',', '.')::numeric
                    ELSE REPLACE(b.promo_numeric_raw, ',', '.')::numeric
                END AS valor_promocional,
                CASE
                    WHEN b.dt_ini_raw ~ '^\\d{4}-\\d{2}-\\d{2}$' THEN TO_DATE(b.dt_ini_raw, 'YYYY-MM-DD')
                    WHEN b.dt_ini_raw ~ '^\\d{2}-\\d{2}-\\d{4}$' THEN TO_DATE(b.dt_ini_raw, 'DD-MM-YYYY')
                    ELSE NULL
                END AS data_inicio_promocao,
                CASE
                    WHEN b.dt_fim_raw ~ '^\\d{4}-\\d{2}-\\d{2}$' THEN TO_DATE(b.dt_fim_raw, 'YYYY-MM-DD')
                    WHEN b.dt_fim_raw ~ '^\\d{2}-\\d{2}-\\d{4}$' THEN TO_DATE(b.dt_fim_raw, 'DD-MM-YYYY')
                    ELSE NULL
                END AS data_fim_promocao,
                CASE
                    WHEN b.dt_atual_raw ~ '^\\d{4}-\\d{2}-\\d{2}$' THEN TO_DATE(b.dt_atual_raw, 'YYYY-MM-DD')
                    WHEN b.dt_atual_raw ~ '^\\d{2}-\\d{2}-\\d{4}$' THEN TO_DATE(b.dt_atual_raw, 'DD-MM-YYYY')
                    ELSE NULL
                END AS data_atualizacao,
                NULLIF(b.origem, '') AS origem,
                NULLIF(b.tipo_cliente, '') AS tipo_cliente,
                NULLIF(b.vendedor_responsavel, '') AS vendedor_responsavel,
                NULLIF(b.observacao, '') AS observacao,
                b.status
            FROM base b;
        SQL);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS vw_precos_processados');
        DB::statement('DROP VIEW IF EXISTS vw_produtos_processados');
    }
};
