<?php

namespace App\Services\Modelos;
use App\Repositories\BaseDatos;
use App\Domain\Pedido;
use App\Domain\Sucursal;

class PedidoService
{

    private Sucursal $sucursal;
    private BaseDatos $dataBase;

    public function __construct()
    {
        $this->dataBase = new BaseDatos();
    }

    public function nuevoPedido($paciente_id)
    {
        $pedido = Pedido::createPedido($paciente_id);

        return $pedido;
    }


    public function asociarSucursalAPedido($sucursal, $pedido)
    {

        $pedido->asociarSucursalAPedido($sucursal);

        return $pedido;
    }

    public function agregarMedicamento($medId, $cantidad, $pedido)
    {
        if (!$pedido) {
            throw new \RuntimeException('No hay pedido en captura para agregar medicamento.');
        }
        $medicamento = $this->dataBase->obtenerMedicamento($medId);
        $pedido->agregarMedicamento($medId, $cantidad, $medicamento);

        return $pedido;
    }

    public function eliminarMedicamento($medId, $pedido)
    {

        if (!$pedido) {
            throw new \RuntimeException('No hay pedido en captura para eliminar un medicamento.');
        }

        $pedido->eliminarMedicamento($medId);

        return $pedido;
    }

    public function obtenerSucursal($cadena_id, $sucursal_id)
    {
        $this->sucursal = $this->dataBase->obtenerSucursal($cadena_id, $sucursal_id);
        return $this->sucursal;
    }

    public function obtenerLineasPedidoActuales($pedido): array
    {

        if (!$pedido) {
            return [];
        }

        $lineas = $pedido->getLineasPedidos();

        return $lineas->map(function ($linea) {

            return [
                'id' => $linea->getMedicamentoId(),
                'name' => $linea->getMedicamento()->getNombre(),
                'quantity' => (int) $linea->getCantidad(),
            ];
        })->values()->all();
    }

    public function obtenerPedid($id)
    {
        $pedidos = $this->dataBase->getPedidos($id);
    }
    public function setCedulaProfesional($cedula, $pedido)
    {
        $pedido->setCedulaProfesional($cedula);
        return $pedido;
    }
    public function obtenerPedidosPorPacienteId($paciente_id)
    {
        return $this->dataBase->getPedidos($paciente_id);
    }
    public function asignarFechaRecoleccion($pedido)
    {
        $pedido->asignarFechaPedido();
        $pedido->asignarFechaRecoleccion();
        return $pedido;
    }
}
