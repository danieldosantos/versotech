<?php

use App\Http\Controllers\DataProcessingController;
use Illuminate\Support\Facades\Route;

Route::post('processar-produtos', [DataProcessingController::class, 'processProducts']);
Route::post('processar-precos', [DataProcessingController::class, 'processPrices']);
Route::get('produtos-com-precos', [DataProcessingController::class, 'listProductsWithPrices']);
// Retorna todos os produtos; quando não existir preço regular, valor é retornado como 0
Route::get('produtos-com-precos-inclusive', [DataProcessingController::class, 'listProductsWithPricesInclusive']);
Route::get('produtos', [DataProcessingController::class, 'listProducts']);
