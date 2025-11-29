<?php

namespace App\Services\Modelos;
use App\Repositories\BaseDatos;
use App\Domain\Pedido;
use App\Domain\Sucursal;
use App\Domain\Paciente;
use App\Domain\Notificacion;

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


    public function asociarSucursalAPedido($sucursal, Pedido $pedido)
    {

        $pedido->asociarSucursalAPedido($sucursal);
        info("pedidoooo", [$pedido->getSucursal()->getNombre()]);
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

    public function cancelarPedido($pedido)
    {
        $pedido->cambiarEstatus('CANCELADO');
        $dlp = $pedido->obtenerDetallesLineas();
        foreach ($dlp as $detalle) {
            $this->dataBase->iniciarTransaccion();
            $inventario = $this->dataBase->obtenerInventario(
                $pedido->getSucursal()->getCadenaId(),
                $pedido->getSucursal()->getSucursalId(),
                $detalle->getMedicamentoId()
            );
            if ($inventario) {
                $inventario->aumentarStock($detalle->getCantidadSurtida());
                $this->dataBase->actualizarInventarioCancelacion($inventario);
                $this->dataBase->commitTransaccion();
            }
            else{
                $this->dataBase->cancelarTransaccion();
            }
        }
        $paciente = $this->dataBase->obtenerPaciente($pedido->getPacienteId());
        $cantidad_penalizacion = $pedido->getCostoTotal() * 0.5;
        $paciente->aplicarPenalizacion($cantidad_penalizacion);
        $this->dataBase->actualizarPaciente($paciente);
        $mensaje = "Su pedido {$pedido->getfolio()} ha sido cancelado. Se ha aplicado una penalización de $${$cantidad_penalizacion} a su cuenta.";
        $notificacion = Notificacion::crear($mensaje, now());
        $paciente->agregarNotificacion($notificacion);
        $this->dataBase->guardarNotificacion($notificacion, $pedido->getfolio(), $paciente->getUser()->getId());
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
    public function buscarPedidoPorId($pedido_id)
    {
        return $this->dataBase->obtenerPedidoPorId($pedido_id);
    }
    public function setCedulaProfesional($cedula, Pedido $pedido)
    {
        $pedido->setCedulaProfesional($cedula);
        return $pedido;
    }
    public function obtenerPedidosPorPacienteId($paciente_id)
    {
        return $this->dataBase->getPedidos($paciente_id);
    }

    public function obtenerPedidoPorFolio($folio)
    {
        return $this->dataBase->getPedidoByFolio($folio);
    }
    public function asignarFechaRecoleccion($pedido)
    {
        $pedido->asignarFechaPedido();
        $pedido->asignarFechaRecoleccion();
        return $pedido;
    }

    public function obtenerPedidosSucursal($cadena_id, $sucursal_id)
    {
        return $this->dataBase->obtenerPedidosPorSucursal($cadena_id, $sucursal_id);
    }
<<<<<<< HEAD
=======
    public function sumarMontoPenalizacion($monto, $pedido)
    {
        $pedido->setMontoPenalizacion($monto);
    }
>>>>>>> arturo
}
