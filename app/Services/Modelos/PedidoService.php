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

        $datosPedido = Session::get('pedido_temporal');

        if (!$datosPedido) {
            throw new \RuntimeException('No hay pedido en captura en la sesión.');
        }

        $pedido = Pedido::createPedidoFromSession($datosPedido);

        $pedido->asociarSucursalAPedido($cadena_id,$sucursal_id);

        Session::put('pedido_temporal', $pedido->toArray());

    }

    public function agregarMedicamento($medId,$cantidad){

        $datosPedido = Session::get('pedido_temporal');

        if (!$datosPedido) {
            throw new \RuntimeException('No hay pedido en captura para agregar medicamento.');
        }

        $pedido = Pedido::createPedidoFromSession($datosPedido);

        $pedido->agregarMedicamento($medId,$cantidad);

        Session::put('pedido_temporal', $pedido->toArray());
    }

    public function eliminarMedicamento($medId)
    {
        $datosPedido = Session::get('pedido_temporal');

        if (!$datosPedido) {
            throw new \RuntimeException('No hay pedido en captura para eliminar un medicamento.');
        }

        $pedido = Pedido::createPedidoFromSession($datosPedido);

        $pedido->eliminarMedicamento($medId);

        Session::put('pedido_temporal', $pedido->toSessionArray());
    }

    public function obtenerSucursal($cadena_id, $sucursal_id){
        $this->sucursal=$this->dataBase->obtenerSucursal($cadena_id,$sucursal_id);
        return $this->sucursal;
    }
}
