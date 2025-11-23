<?php

namespace App\Http\Dominio;
use App\Http\Dominio\Sucursal;
use App\Http\ServiciosTecnicos\BaseDatos;

class ModeloDominio
{

    private Pedido $pedido;
    private Sucursal $sucursal;
    private BaseDatos $dataBase;

    public function __construct(){
        $this->dataBase=new BaseDatos();
    }
    public function nuevoPedido($paciente){
        $this->pedido=new Pedido($paciente);
        return $this->pedido;
    }
    public function obtenerSucursales(){

    }
    public function asociarSucursalAPedido($cadena_id,$sucursal_id){
        $this->sucursal=new Sucursal($this->dataBase->obtenerSucursal($cadena_id,$sucursal_id));
        $this->pedido->asociarSucursalAPedido($this->sucursal);
    }
    public function agregarMedicamento($medId,$cantidad){
        $this->pedido->agregarMedicamento($medId,$cantidad);
    }
}
