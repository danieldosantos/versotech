<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DataProcessingController extends Controller
{
    public function processProducts(): JsonResponse
    {
        DB::transaction(function () {
            DB::table('produto_insercao')->delete();

            // Usar upsert para evitar violação de chave única caso a view contenha códigos duplicados
            // Primeiro deduplicamos a view por `codigo` usando ROW_NUMBER(), mantendo a linha mais recente por data_cadastro
            DB::statement(
                "WITH dedup AS (
                    SELECT *, ROW_NUMBER() OVER (PARTITION BY codigo ORDER BY COALESCE(data_cadastro, '1900-01-01') DESC) AS rn
                    FROM vw_produtos_processados
                )
                INSERT INTO produto_insercao (codigo, nome, categoria, subcategoria, descricao, fabricante, modelo, cor, peso_kg, largura_cm, altura_cm, profundidade_cm, unidade, ativo, data_cadastro, created_at, updated_at)
                SELECT codigo, nome, categoria, subcategoria, descricao, fabricante, modelo, cor, peso_kg, largura_cm, altura_cm, profundidade_cm, unidade, ativo, data_cadastro, NOW(), NOW()
                FROM dedup WHERE rn = 1
                ON CONFLICT (codigo) DO UPDATE SET
                    nome = EXCLUDED.nome,
                    categoria = EXCLUDED.categoria,
                    subcategoria = EXCLUDED.subcategoria,
                    descricao = EXCLUDED.descricao,
                    fabricante = EXCLUDED.fabricante,
                    modelo = EXCLUDED.modelo,
                    cor = EXCLUDED.cor,
                    peso_kg = EXCLUDED.peso_kg,
                    largura_cm = EXCLUDED.largura_cm,
                    altura_cm = EXCLUDED.altura_cm,
                    profundidade_cm = EXCLUDED.profundidade_cm,
                    unidade = EXCLUDED.unidade,
                    ativo = EXCLUDED.ativo,
                    data_cadastro = EXCLUDED.data_cadastro,
                    updated_at = NOW()"
            );
        });

        $total = DB::table('produto_insercao')->count();

        return response()->json([
            'message' => 'Produtos processados com sucesso.',
            'total' => $total,
        ]);
    }

    public function processPrices(): JsonResponse
    {
        DB::transaction(function () {
            DB::table('preco_insercao')->delete();

            // Usar upsert para evitar violação de chave única caso a view contenha códigos repetidos
            // Deduplicar por codigo_produto mantendo a linha mais recente por data_atualizacao antes do upsert
            DB::statement(
                "WITH dedup AS (
                    SELECT *, ROW_NUMBER() OVER (PARTITION BY codigo_produto ORDER BY COALESCE(data_atualizacao, NOW()) DESC) AS rn
                    FROM vw_precos_processados
                )
                INSERT INTO preco_insercao (codigo_produto, valor, moeda, percentual_desconto, percentual_acrescimo, valor_promocional, data_inicio_promocao, data_fim_promocao, data_atualizacao, origem, tipo_cliente, vendedor_responsavel, observacao, status, created_at, updated_at)
                SELECT codigo_produto, valor, moeda, percentual_desconto, percentual_acrescimo, valor_promocional, data_inicio_promocao, data_fim_promocao, data_atualizacao, origem, tipo_cliente, vendedor_responsavel, observacao, status, NOW(), NOW()
                FROM dedup WHERE rn = 1
                ON CONFLICT (codigo_produto) DO UPDATE SET
                    valor = EXCLUDED.valor,
                    moeda = EXCLUDED.moeda,
                    percentual_desconto = EXCLUDED.percentual_desconto,
                    percentual_acrescimo = EXCLUDED.percentual_acrescimo,
                    valor_promocional = EXCLUDED.valor_promocional,
                    data_inicio_promocao = EXCLUDED.data_inicio_promocao,
                    data_fim_promocao = EXCLUDED.data_fim_promocao,
                    data_atualizacao = EXCLUDED.data_atualizacao,
                    origem = EXCLUDED.origem,
                    tipo_cliente = EXCLUDED.tipo_cliente,
                    vendedor_responsavel = EXCLUDED.vendedor_responsavel,
                    observacao = EXCLUDED.observacao,
                    status = EXCLUDED.status,
                    updated_at = NOW()"
            );
        });

        $total = DB::table('preco_insercao')->count();

        return response()->json([
            'message' => 'Preços processados com sucesso.',
            'total' => $total,
        ]);
    }

    public function listProductsWithPrices(): JsonResponse
    {
        $produtos = DB::table('produto_insercao as p')
            ->leftJoin('preco_insercao as pr', 'p.codigo', '=', 'pr.codigo_produto')
            ->select([
                'p.codigo',
                'p.nome',
                'p.categoria',
                'p.subcategoria',
                'p.descricao',
                'p.fabricante',
                'p.modelo',
                'p.cor',
                'p.peso_kg',
                'p.largura_cm',
                'p.altura_cm',
                'p.profundidade_cm',
                'p.unidade',
                'p.data_cadastro',
                'pr.valor',
                'pr.valor_promocional',
                'pr.percentual_desconto',
                'pr.percentual_acrescimo',
                'pr.moeda',
                'pr.data_inicio_promocao',
                'pr.data_fim_promocao',
            ])
            // Apenas produtos que têm preço regular (valor) devem ser listados
            // Excluir preços nulos e também valores iguais ou menores que zero
            ->whereNotNull('pr.valor')
            ->where('pr.valor', '>', 0)
            ->orderBy('p.nome')
            ->get();

        return response()->json([
            'data' => $produtos,
        ]);
    }

    /**
     * Retorna todos os produtos; quando não houver preço regular definido, retorna valor = 0.
     * Usado pelo fluxo de "Processar Preços" para exibir todos os itens (com preço zerado quando ausente).
     */
    public function listProductsWithPricesInclusive(): JsonResponse
    {
        $produtos = DB::table('produto_insercao as p')
            ->leftJoin('preco_insercao as pr', 'p.codigo', '=', 'pr.codigo_produto')
            ->select([
                'p.codigo',
                'p.nome',
                'p.categoria',
                'p.subcategoria',
                'p.descricao',
                'p.fabricante',
                'p.modelo',
                'p.cor',
                'p.peso_kg',
                'p.largura_cm',
                'p.altura_cm',
                'p.profundidade_cm',
                'p.unidade',
                'p.data_cadastro',
                // coalesce para garantir 0 quando não houver preço
                DB::raw('COALESCE(pr.valor, 0) as valor'),
                'pr.valor_promocional',
                'pr.percentual_desconto',
                'pr.percentual_acrescimo',
                'pr.moeda',
                'pr.data_inicio_promocao',
                'pr.data_fim_promocao',
            ])
            ->orderBy('p.nome')
            ->get();

        return response()->json([
            'data' => $produtos,
        ]);
    }

    public function listProducts(): JsonResponse
    {
        $produtos = DB::table('produto_insercao as p')
            ->select([
                'p.codigo',
                'p.nome',
                'p.categoria',
                'p.subcategoria',
                'p.fabricante',
                'p.modelo',
                'p.cor',
                'p.peso_kg',
                'p.largura_cm',
                'p.altura_cm',
                'p.profundidade_cm',
                'p.unidade',
                'p.data_cadastro',
            ])
            ->orderBy('p.nome')
            ->get();

        return response()->json([
            'data' => $produtos,
        ]);
    }
}
