<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$prodCount = DB::table('produtos_base')->count();
$priceCount = DB::table('precos_base')->count();
$prodSample = DB::table('produtos_base')->select('prod_cod','prod_nome')->limit(5)->get();
$priceSample = DB::table('precos_base')->select('prc_cod_prod','prc_valor')->limit(5)->get();

echo "produtos_base count: {$prodCount}\n";
foreach($prodSample as $p){ echo "- [{$p->prod_cod}] {$p->prod_nome}\n"; }

echo "\nprecos_base count: {$priceCount}\n";
foreach($priceSample as $p){ echo "- [{$p->prc_cod_prod}] {$p->prc_valor}\n"; }
