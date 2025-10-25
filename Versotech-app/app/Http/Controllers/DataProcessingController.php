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

            DB::statement(
                "INSERT INTO produto_insercao (codigo, nome, categoria, subcategoria, descricao, fabricante, modelo, cor, peso_kg, largura_cm, altura_cm, profundidade_cm, unidade, ativo, data_cadastro, created_at, updated_at)
                 SELECT codigo, nome, categoria, subcategoria, descricao, fabricante, modelo, cor, peso_kg, largura_cm, altura_cm, profundidade_cm, unidade, ativo, data_cadastro, NOW(), NOW()
                 FROM vw_produtos_processados"
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

            DB::statement(
                "INSERT INTO preco_insercao (codigo_produto, valor, moeda, percentual_desconto, percentual_acrescimo, valor_promocional, data_inicio_promocao, data_fim_promocao, data_atualizacao, origem, tipo_cliente, vendedor_responsavel, observacao, status, created_at, updated_at)
                 SELECT codigo_produto, valor, moeda, percentual_desconto, percentual_acrescimo, valor_promocional, data_inicio_promocao, data_fim_promocao, data_atualizacao, origem, tipo_cliente, vendedor_responsavel, observacao, status, NOW(), NOW()
                 FROM vw_precos_processados"
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
            ->orderBy('p.nome')
            ->get();

        return response()->json([
            'data' => $produtos,
        ]);
    }
}
