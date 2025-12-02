<?php

namespace App\Services\Modelos;

use App\Domain\DetalleLineaPedido;
use App\Domain\LineaPedido;
use App\Repositories\BaseDatos;
use App\Domain\Pedido;
use App\Models\Pedido as ModelsPedido;
use App\Domain\Sucursal;
use App\Domain\Paciente;
use App\Domain\Notificacion;
use Illuminate\Support\Collection;
use Carbon\Carbon;
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
    $this->pacienteService = new PacienteService();
  }

  public function nuevoPedido($paciente_id): Pedido
  {
    $montoPenalizacion = $this->pacienteService->getMontoPenalizacion($paciente_id);
    $pedidos = $this->dataBase->getPedidos($paciente_id);
    $pedidosActivos = $this->pacienteService->getPedidosActivos($pedidos);
    if ($montoPenalizacion > 0 && $pedidosActivos != 0) {
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

  public function asociarSucursalAPedido(Sucursal $sucursal, Pedido $pedido): Pedido
  {
    $pedido->setSucursal($sucursal);
    return $pedido;
  }

  public function agregarMedicamento(int $medId, int $cantidad, Pedido $pedido): Pedido
  {
    if (!$pedido) {
      throw new \RuntimeException('No hay pedido en captura para agregar medicamento.');
    }
    $medicamento = $this->dataBase->getMedicamento($medId);
    $pedido->agregarMedicamento($medId, $cantidad, $medicamento);

    return $pedido;
  }

  public function eliminarMedicamento(int $medId, Pedido $pedido): Pedido
  {
    if (!$pedido) {
      throw new \RuntimeException('No hay pedido en captura para eliminar un medicamento.');
    }

    $pedido->eliminarMedicamento($medId);
    return $pedido;
  }
  //TODO borrar cancelarPedido que no valida fecha de surtido
  public function cancelarPedido(Pedido $pedido): Pedido
  {
    if (strtolower($pedido->getEstatus()) !== ModelsPedido::ESTATUS_SURTIDO) {
      throw new Exception("Solo puedes cancelar pedidos si el pedido que esta surtido");
    }
    $pedido->cambiarEstatus(ModelsPedido::ESTATUS_CANCELADO);
    $this->dataBase->guardarCambioEstatusPedido($pedido);

    $dlp = $pedido->getAllDetalles();

    $this->dataBase->iniciarTransaccion();
    try {
      foreach ($dlp as $detalle) {
        /** @var DetalleLineaPedido $detalle */
        $inventario = $this->dataBase->getInventario(
          $detalle->getSucursal()->getCadenaId(),
          $detalle->getSucursal()->getSucursalId(),
          $detalle->getMedicamentoId()
        );

        if (!$inventario) {
          throw new \RuntimeException('Inventario no encontrado para medicamento ' . $detalle->getMedicamentoId());
        }

        $inventario->aumentarStock($detalle->getCantidadSurtida());
        $this->dataBase->actualizarInventarioCancelacion($inventario);
      }

      $this->dataBase->commitTransaccion();
    } catch (\Throwable $e) {
      $this->dataBase->cancelarTransaccion();
      throw $e;
    }

    $paciente = $this->dataBase->getPaciente($pedido->getPacienteId());
    $cantidadPenalizacion = $pedido->getCostoTotal() * 0.5;
    $paciente->sumarMontoPenalizacion($cantidadPenalizacion);
    $mensaje = "Su pedido {$pedido->getFolio()} ha sido cancelado. Se ha aplicado una penalización de s{$cantidadPenalizacion} a su cuenta.";
    $notificacion = Notificacion::crear($mensaje, Carbon::now());
    $paciente->agregarNotificacion($notificacion);
    $this->dataBase->guardarNotificacion($notificacion, $paciente->getUser()->getId(), $pedido->getFolio());
    $this->dataBase->actualizarPaciente($paciente);


    return $pedido;
  }

  public function cancelarPedidoSucursal(Pedido $pedido): Pedido
  {
    if (strtolower($pedido->getEstatus()) !== ModelsPedido::ESTATUS_SURTIDO) {
      throw new \RuntimeException('Solo se pueden cancelar pedidos que están surtidos.');
    }

    if ($pedido->getFechaSurtido() && Carbon::now()->diffInHours($pedido->getFechaSurtido()) < 24) {
      throw new \RuntimeException('No puedes cancelar el pedido antes de 24 horas de haber sido surtido. El cliente aún tiene tiempo para recogerlo.');
    }
    $this->dataBase->iniciarTransaccion();


    try {
      $pedido->cambiarEstatus(ModelsPedido::ESTATUS_CANCELADO);
      $this->dataBase->guardarCambioEstatusPedido($pedido);

      $dlp = $pedido->getAllDetalles();
      foreach ($dlp as $detalle) {
        /** @var DetalleLineaPedido $detalle */
        $inventario = $this->dataBase->getInventario(
          $detalle->getSucursal()->getCadenaId(),
          $detalle->getSucursal()->getSucursalId(),
          $detalle->getMedicamentoId()
        );

        if ($inventario) {
          $inventario->aumentarStock($detalle->getCantidadSurtida());
          $this->dataBase->actualizarInventarioCancelacion($inventario);
        }
      }
      // Aplicar penalización al paciente (asumiendo que es por no recoger)
      $paciente = $this->dataBase->getPaciente($pedido->getPacienteId());
      $cantidadPenalizacion = $pedido->getCostoTotal() * 0.5;
      $paciente->sumarMontoPenalizacion($cantidadPenalizacion);
      $mensaje = "Su pedido {$pedido->getFolio()} ha sido cancelado por la sucursal. Se ha aplicado una penalización de \${$cantidadPenalizacion}.";
      $notificacion = Notificacion::crear($mensaje, Carbon::now());
      $paciente->agregarNotificacion($notificacion);
      $this->dataBase->guardarNotificacion($notificacion, $paciente->getUser()->getId(), $pedido->getFolio());
      $this->dataBase->actualizarPaciente($paciente);

      $this->dataBase->commitTransaccion();
    } catch (\Throwable $e) {
      $this->dataBase->cancelarTransaccion();
      throw $e;
    }

    return $pedido;
  }


  public function getSucursal(string $cadenaId, string $sucursalId): Sucursal
  {
    $this->sucursal = $this->dataBase->getSucursal($cadenaId, $sucursalId);
    return $this->sucursal;
  }

  public function getLineasPedidoActuales(?Pedido $pedido): array
  {
    if (!$pedido) {
      return [];
    }

    $lineas = $pedido->getLineasPedidos();

    return $lineas->map(function (LineaPedido $linea) {
      return [
        'id' => $linea->getMedicamentoId(),
        'name' => $linea->getMedicamento()->getNombre(),
        'quantity' => (int) $linea->getCantidad(),
      ];
    })->values()->all();
  }

  public function getPedidoId(int $id): Collection
  {
    return $this->dataBase->getPedidos($id);
  }

  public function setCedulaProfesional(string $cedula, Pedido $pedido): Pedido
  {
    $pedido->setCedulaProfesional($cedula);
    return $pedido;
  }

  public function getPedidosPorPacienteId(int $paciente_id): Collection
  {
    return $this->dataBase->getPedidos($paciente_id);
  }

  public function getPedidoPorFolio(string $folio): ?Pedido
  {
    return $this->dataBase->getPedidoByFolio($folio);
  }
  public function asignarFechaRecoleccion(Pedido $pedido): Pedido
  {
    $pedido->asignarFechaPedido();
    $pedido->asignarFechaRecoleccion();
    return $pedido;
  }

  public function getPedidosSucursal(string $cadenaId, string $sucursalId): Collection
  {
    return $this->dataBase->getPedidosPorSucursal($cadenaId, $sucursalId);
  }


  public function obtenerPedidoPorFolio($folio)
  {
    return $this->dataBase->getPedidoByFolio($folio);
  }

  public function sumarMontoPenalizacion($monto, Pedido $pedido)
  {
    $pedido->sumarMontoPenalizacion($monto);
  }

  public function marcarPedidoComoSurtido(Pedido $pedido)
  {
    if (strtolower($pedido->getEstatus()) !== ModelsPedido::ESTATUS_CONFIRMADO) {
      throw new \RuntimeException('Solo se pueden marcar como surtidos los pedidos con estatus Confirmado.');
    }
    $this->dataBase->iniciarTransaccion();
    try {
      $pedido->cambiarEstatus(ModelsPedido::ESTATUS_SURTIDO);
      $pedido->setFechaSurtido(Carbon::now());
      $this->dataBase->guardarCambioEstatusPedido($pedido);

      \App\Jobs\SendOrderReadyNotification::dispatch($pedido->getFolio(), $pedido->getPacienteId())
        ->delay(now()->addSeconds(30));

      $this->dataBase->commitTransaccion();
    } catch (\Exception $e) {
      $this->dataBase->cancelarTransaccion();
      throw $e;
    }

    return $pedido;
  }

  public function deshacerSurtido(Pedido $pedido)
  {
    if (strtolower($pedido->getEstatus()) !== ModelsPedido::ESTATUS_SURTIDO) {
      throw new \RuntimeException('Solo se puede deshacer el estado de pedidos que están surtidos.');
    }

    $this->dataBase->iniciarTransaccion();
    try {
      $pedido->cambiarEstatus(ModelsPedido::ESTATUS_CONFIRMADO);
      $pedido->setFechaSurtido(null);
      $this->dataBase->guardarCambioEstatusPedido($pedido);
      $this->dataBase->commitTransaccion();
    } catch (\Exception $e) {
      $this->dataBase->cancelarTransaccion();
      throw $e;
    }

    return $pedido;
  }

  public function obtenerPedidosSucursal($cadena_id, $sucursal_id)
  {
    return $this->dataBase->obtenerPedidosPorSucursal($cadena_id, $sucursal_id);
  }

  public function buscarPorFolio($folio_pedido, $apellido)
  {
    $pedido = $this->dataBase->getPedidoByFoliosyApellido($folio_pedido, $apellido);

    if (!$pedido) {
      return null;
    }

    return $pedido;
  }
}
