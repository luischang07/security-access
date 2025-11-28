<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PedidoApiController;

Route::get('/pedidos/{paciente}', [PedidoApiController::class, 'pedidosPorPaciente']);
Route::get('/pedido/{folio}', [PedidoApiController::class, 'pedidoPorFolio']);

