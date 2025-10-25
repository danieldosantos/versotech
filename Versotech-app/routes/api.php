<?php

use App\Http\Controllers\DataProcessingController;
use Illuminate\Support\Facades\Route;

Route::post('processar-produtos', [DataProcessingController::class, 'processProducts']);
Route::post('processar-precos', [DataProcessingController::class, 'processPrices']);
Route::get('produtos-com-precos', [DataProcessingController::class, 'listProductsWithPrices']);
