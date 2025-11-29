<?php

namespace App\Services\Modelos;
use App\Domain\Pedido;
use App\Domain\Sucursal;
use App\Domain\DetalleLineaPedido;
use App\Domain\LineaPedido;
use App\Domain\LineaInventario;
use App\Services\Modelos\PacienteService;
use App\Services\Modelos\PedidoService;
use App\Services\Modelos\SucursalService;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Collection;
use App\Repositories\BaseDatos;
use DB;


class GestorDeSurtido
{
  private SucursalService $sucursalService;
  private PedidoService $pedidoService;
  private PacienteService $pacienteService;
  private $SinStock;
  private BaseDatos $dataBase;
  public function __construct(SucursalService $sucursalService, PedidoService $pedidoService, PacienteService $pacienteService)
  {
    $this->sucursalService = $sucursalService;
    $this->pedidoService = $pedidoService;
    $this->pacienteService = $pacienteService;
    $this->SinStock = collect();
    $this->dataBase = new BaseDatos();
  }

  public function surtir(Pedido $pedido)
  {

    $sucsel = $pedido->getSucursal();

    $ldp = $pedido->getLineasPedidos();

    foreach ($ldp as $lineaPedido) {
      $cantidadSurtida = 0;
      $ldi = $this->sucursalService->getLineaInventario($sucsel->getCadenaId(), $sucsel->getSucursalId(), $lineaPedido->getMedicamentoId());
      if ($ldi->getStockDisponible() > 0) {
        $cantidadSurtida = $lineaPedido->getCantidad() - $ldi->getStockDisponible() <= 0 ? $lineaPedido->getCantidad() : $ldi->getStockDisponible();

        $lineaPedido->crearDetalleLineaPedido($ldi->getPrecioUnitario(), $cantidadSurtida, $sucsel, $lineaPedido->getMedicamentoId());
      }
      if ($cantidadSurtida < $lineaPedido->getCantidad()) {
        $this->SinStock->push($lineaPedido);
      }
    }

    if ($this->SinStock->count() > 0) {
      $sucCercanas = $this->sucursalService->calculaSucCercanas($sucsel->getCadenaId(), $sucsel->getSucursalId());
      $this->CalculaFaltantes($this->SinStock, $sucCercanas);
    }
    return $pedido;
  }

  public function CalculaFaltantes($SinStock, $sucCercanas)
  {

    foreach ($sucCercanas as $suc) {
      foreach ($SinStock as $ldp) {
        $ldi = $this->sucursalService->getLineaInventario($suc->getCadenaId(), $suc->getSucursalId(), $ldp->getMedicamentoId());
        $cantFaltante = $ldp->getCantidadFaltante();
        $cantidadSurtida = 0;
        if ($ldi->getStockDisponible() > 0 && $cantFaltante > 0) {
          $cantidadSurtida = $cantFaltante - $ldi->getStockDisponible() <= 0 ? $cantFaltante : $ldi->getStockDisponible();

          $ldp->crearDetalleLineaPedido($ldi->getPrecioUnitario(), $cantidadSurtida, $suc, $ldp->getMedicamentoId());

          if ($cantidadSurtida == $cantFaltante) {
            $this->SinStock = $this->SinStock->reject(function ($item) use ($ldp) {
              return $item === $ldp; // Elimina si es el mismo objeto
            });
          }
        }
      }
    }
  }
  public function confirmarPedido($pedido)
  {
    $this->SinStock = collect();
    $ldp = $pedido->getLineasPedidos();

    foreach ($ldp as $linea) {
      $detalles = $linea->getDetalles();
      foreach ($detalles as $dlp) {
        $existencia = $this->sucursalService->actualizarInventario($dlp->getCantidadSurtida(), $linea->getMedicamentoId(), $dlp->getSucursal());
        if ($existencia) {
          $this->SinStock->push($linea);
          $pedido->eliminarDetalle($dlp);
        } else {
          //Añadir sucursal a la ruta
          $pedido->añadirARuta($dlp->getSucursal());

        }
      }
    }
    if ($this->SinStock->count() > 0) {
      $sucCercanas = $this->sucursalService->calculaSucCercanas($pedido->getSucursal()->getCadenaId(), $pedido->getSucursal()->getSucursalId());
      $this->calculaFaltantesWithUpdate($this->SinStock, $sucCercanas, $pedido);
    }
    $pedido->setEstatus();
    $pedido->calcularTotales();

    $montoPenalizacion = $this->pacienteService->getMontoPenalizacion($pedido->getPacienteId());
    info("Monto penalización aplicada: $montoPenalizacion");
    $pedido->setMontoPenalizacion((float) $montoPenalizacion);
    $this->guardarPedido($pedido);
    return $pedido;
  }

  private function calculaFaltantesWithUpdate($SinStock, $sucCercanas, $pedido)
  {
    foreach ($sucCercanas as $suc) {
      foreach ($SinStock as $ldp) {
        $ldi = $this->sucursalService->getLineaInventario($suc->getCadenaId(), $suc->getSucursalId(), $ldp->getMedicamentoId());
        $cantFaltante = $ldp->getCantidadFaltante();
        $cantidadSurtida = 0;
        if ($ldi->getStockDisponible() > 0 && $cantFaltante > 0) {
          $cantidadSurtida = $ldp->getCantidad() - $ldi->getStockDisponible() <= 0 ? $ldp->getCantidad() : $ldi->getStockDisponible();

          $ldp->crearDetalleLineaPedido($ldi->getPrecioUnitario(), $cantidadSurtida, $suc, $ldp->getMedicamentoId());
          $this->sucursalService->actualizarInventario($cantidadSurtida, $ldp->getMedicamentoId(), $suc);
          $pedido->añadirARuta($suc);
          if ($cantidadSurtida == $cantFaltante) {
            $this->SinStock = $this->SinStock->reject(function ($item) use ($ldp) {
              return $item === $ldp;
            });
          }
        }
      }
    }
  }


  private function guardarPedido(Pedido $pedido)
  {
    DB::transaction(function () use ($pedido) {

      $pedidoBD = $this->dataBase->guardarPedido($pedido);

      $folioPedido = $pedidoBD->folio_pedido;

      $pedido->asignarFolio($folioPedido);

      foreach ($pedido->getLineasPedidos() as $lineaPedido) {
        $lineaBD = $this->dataBase->guardarLineaPedido($lineaPedido, $folioPedido);

        $idLinea = $lineaBD->id_linea_pedido;

        foreach ($lineaPedido->getDetalles() as $detallelinea) {
          $this->dataBase->guardarDetalleLineaPedido(
            $detallelinea,
            $folioPedido,
            $idLinea
          );
        }
      }

      $orden = 0;
      foreach ($pedido->getRuta() as $sucursal) {
        $this->dataBase->guardarRutaRecoleccion([
          'folio_pedido' => $folioPedido,
          'cadena_id' => $sucursal->getCadenaId(),
          'sucursal_id' => $sucursal->getSucursalId(),
          'orden' => $orden++,
        ]);
      }
    });
  }

}