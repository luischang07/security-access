<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PedidoApiController;

Route::get('/pedidos/{paciente_id}', [PedidoApiController::class, 'getPedidos']);
