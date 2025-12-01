<?php

namespace App\Services\Modelos;
use App\Repositories\BaseDatos;
use App\Domain\Pedido;
use App\Domain\Sucursal;
use App\Domain\Paciente;
use App\Domain\Notificacion;
use App\Services\Modelos\PacienteService;
use Exception;
use function PHPUnit\Framework\throwException;

class PedidoService
{

    private Sucursal $sucursal;
    private PacienteService $pacienteService;
    private BaseDatos $dataBase;

    public function __construct()
    {
        $this->dataBase = new BaseDatos();
        $this->pacienteService=new PacienteService();
    }

    public function nuevoPedido($paciente_id)
    {
        $montoPenalizacion = $this->pacienteService->getMontoPenalizacion($paciente_id);
        $pedidos=$this->dataBase->getPedidos($paciente_id);
        $pedidosActivos=$this->pacienteService->getPedidosActivos($pedidos);
        if ($montoPenalizacion > 0 && $pedidosActivos!=0) {
            throw new Exception("No puedes realizar pedidos mientras tengas una penalización pendiente y un pedido activo");
        }
        $pedido = Pedido::createPedido($paciente_id);

        return $pedido;
    }

    public function reiniciarParaCaptura(Pedido $pedido): Pedido
    {
        $pedido->reiniciarParaCaptura();
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
        if(strtolower($pedido->getEstatus()) !== 'surtido'){
            throw new Exception("Solo puedes cancelar pedidos si el pedido ya esta surtido");
        }
        $pedido->cambiarEstatus('Cancelado');
        $this->dataBase->guardarCambioEstatusPedido($pedido);
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
            } else {
                $this->dataBase->cancelarTransaccion();
            }
        }
        $paciente = $this->dataBase->obtenerPaciente($pedido->getPacienteId());
        $cantidad_penalizacion = $pedido->getCostoTotal() * 0.5;
        $paciente->setMontoPenalizacion($cantidad_penalizacion);
        $this->dataBase->actualizarPaciente($paciente);
        $mensaje = "Su pedido {$pedido->getfolio()} ha sido cancelado. Se ha aplicado una penalización de s{$cantidad_penalizacion} a su cuenta.";
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
    public function sumarMontoPenalizacion($monto, $pedido)
    {
        $pedido->setMontoPenalizacion($monto);
    }

    public function marcarPedidoComoSurtido(Pedido $pedido)
    {
        if(strtolower($pedido->getEstatus()) !== 'confirmado'){
            throw new \RuntimeException('Solo se pueden marcar como surtidos los pedidos con estatus Confirmado.');
        }
        $this->dataBase->iniciarTransaccion();
        try {
            $pedido->cambiarEstatus('Surtido');
            $this->dataBase->guardarCambioEstatusPedido($pedido);
            $mensaje = "Su pedido {$pedido->getfolio()} está listo para ser recogido. Tienes 48 horas para recogerlo en la sucursal {$pedido->getSucursal()->getNombre()}.";
            $notificacion = Notificacion::crear($mensaje, now());
            $paciente = $this->dataBase->obtenerPaciente($pedido->getPacienteId());
            $paciente->agregarNotificacion($notificacion);
            $this->dataBase->guardarNotificacion($notificacion, $pedido->getfolio(), $paciente->getUser()->getId());
            $this->dataBase->commitTransaccion();
        } catch (\Exception $e) {
            $this->dataBase->cancelarTransaccion();
            throw $e;
        }

        return $pedido;
    }
}
