<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Dominio\ModeloDominio;

class GestionPedidoController extends Controller
{
    private ModeloDominio $modeloDominio;
    public function nuevoPedido(){
        $this->modeloDominio->nuevoPedido();
        return $this->modeloDominio;
    }
}
