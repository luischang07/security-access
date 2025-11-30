<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PedidoApiController;

Route::get('/patient/orders/{folio}', [PedidoApiController::class, 'getPedidos']);
