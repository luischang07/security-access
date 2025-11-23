<?php

namespace App\Http\Dominio;
use App\Http\Dominio\Sucursal;

class ModeloDominio
{

    private Pedido $pedido;

    public function nuevoPedido($paciente){
        $this->pedido=new Pedido($paciente);
        return $this->pedido;
    }
    public function obtenerSucursales(){

    }
    public function asociarSucursalAPedido($sucursal){
        $this->pedido->asignarSucursal($this->sucursal);
    }
    public function agregarMedicamento($medId,$cantidad){
        $this->pedido->agregarMedicamento($medId,$cantidad);
    }
}
