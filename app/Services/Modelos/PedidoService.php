<?php

namespace App\Services\Modelos;
use App\Repositories\BaseDatos;
use App\Domain\Pedido;
use App\Domain\Sucursal;
use Illuminate\Support\Facades\Session;

class PedidoService
{

    private Pedido $pedido;
    private Sucursal $sucursal;
    private BaseDatos $dataBase;

    public function __construct(){
        $this->dataBase=new BaseDatos();
    }
    
    public function nuevoPedido($paciente_id){
        $this->pedido=Pedido::createPedido($paciente_id);

        Session::put('pedido_temporal', $this->pedido->toArray());

        return $this->pedido;
    }

    public function asociarSucursalAPedido($sucursal_id,$cadena_id){

        $datosPedido = Session::get('pedido_temporal', []);

        $pedido = Pedido::createPedidoFromSession($datosPedido);

        $pedido->asociarSucursalAPedido($sucursal_id,$cadena_id);

        Session::put('pedido_temporal', $pedido->toArray());

    }

    public function agregarMedicamento($medId,$cantidad){

        $datosPedido = Session::get('pedido_temporal', []);

        $pedido = Pedido::createPedidoFromSession($datosPedido);

        $pedido->agregarMedicamento($medId,$cantidad);

        Session::put('pedido_temporal', $pedido->toArray());
    }

    public function obtenerSucursal(Array $sucursal){

        $this->sucursal=$this->dataBase->obtenerSucursal($sucursal[0],$sucursal[1]);
        return $this->sucursal;
    }
}
