<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Dominio\ModeloDominio;

class GestionPedidoController extends Controller
{
    private ModeloDominio $modeloDominio;
    public function nuevoPedido($paciente){
        $this->modeloDominio->nuevoPedido($paciente);
        return $this->modeloDominio;
    }
    public function seleccionarSucursal($sucursal){
        $this->modeloDominio->asociarSucursalAPedido($sucursal);
    }
}
