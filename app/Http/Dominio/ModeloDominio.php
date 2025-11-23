<?php

namespace App\Http\ModeloDominio;

class ModeloDominio
{
    public function nuevoPedido($paciente){
        return new Pedido($paciente);
    }
    public function obtenerSucursal(){
        
    }
}