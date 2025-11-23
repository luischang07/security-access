<?php

namespace App\Http\ModeloDominio;

class ModeloDominio
{
    private Pedido $pedido;
    private Sucursal $sucursal;
    public function nuevoPedido($paciente){
        $this->pedido=new Pedido($paciente);
        return $this->pedido;
    }
    public function obtenerSucursales(){

    }
    public function asociarSucursalAPedido($sucursal){
        $this->pedido->asignarSucursal($this->sucursal);
    }
}