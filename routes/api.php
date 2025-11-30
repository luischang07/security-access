<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PedidoApiController;

Route::get('/pedido/{folio}', [PedidoApiController::class, 'getPedido']);
