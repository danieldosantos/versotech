<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$rows = DB::table('vw_precos_processados')
    ->select('codigo_produto','data_inicio_promocao','data_fim_promocao','data_atualizacao')
    ->orderBy('codigo_produto')
    ->get();

echo json_encode($rows, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);

