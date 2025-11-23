<?php

namespace App\Http\ModeloDominio;
use App\Http\Dominio\Sucursal;

class ModeloDominio
{
    private $sucursal;

    public function __construct(Sucursal $sucursal){
        $this->sucursal = $sucursal;
    }

    public function nuevoPedido($paciente){
        return new Pedido($paciente);
    }

}
