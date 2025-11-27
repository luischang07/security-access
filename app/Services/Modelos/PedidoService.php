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

    public function __construct()
    {
        $this->dataBase = new BaseDatos();
    }

    public function nuevoPedido($paciente_id)
    {
        $this->pedido = Pedido::createPedido($paciente_id);

        Session::put('pedido_temporal', serialize($this->pedido));

        return $this->pedido;
    }


    public function asociarSucursalAPedido($sucursal_id, $cadena_id)
    {

        $pedido = unserialize(Session::get('pedido_temporal'));

        $pedido->asociarSucursalAPedido($cadena_id, $sucursal_id);
        Session::forget('pedido_temporal');
        Session::put('pedido_temporal', serialize($pedido));

    }

    public function agregarMedicamento($medId, $cantidad)
    {

        $pedido = unserialize(Session::get('pedido_temporal'));
        Session::forget('pedido_temporal');

        if (!$pedido) {
            throw new \RuntimeException('No hay pedido en captura para agregar medicamento.');
        }
        $medicamento = $this->dataBase->obtenerMedicamento($medId);
        $pedido->agregarMedicamento($medId, $cantidad, $medicamento);
        Session::put('pedido_temporal', serialize($pedido));
    }

    public function eliminarMedicamento($medId)
    {
        $pedido = unserialize(Session::get('pedido_temporal'));

        if (!$pedido) {
            throw new \RuntimeException('No hay pedido en captura para eliminar un medicamento.');
        }

        $pedido->eliminarMedicamento($medId);

        Session::put('pedido_temporal', serialize($pedido));
    }

    public function obtenerSucursal($cadena_id, $sucursal_id)
    {
        $this->sucursal = $this->dataBase->obtenerSucursal($cadena_id, $sucursal_id);
        return $this->sucursal;
    }

    public function obtenerLineasPedidoActuales(): array
    {
        $datosPedido = unserialize(Session::get('pedido_temporal'));

        if (!$datosPedido) {
            return [];
        }

        $lineas = $datosPedido->getLineasPedidos();

        return $lineas->map(function ($linea) {

            return [
                'id' => $linea->getMedicamentoId(),
                'name' => $linea->getMedicamento()->getNombre(),
                'quantity' => (int) $linea->getCantidad(),
            ];
        })->values()->all();
    }
}
