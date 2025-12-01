<?php

namespace App\Services\Modelos;

use App\Domain\LineaPedido;
use App\Domain\Pedido;
use App\Domain\Sucursal;
use App\Models\Pedido as PedidoModel;
use App\Services\Modelos\PedidoService;
use App\Services\Modelos\SucursalService;
use App\Services\GeoLocationService;
use App\Repositories\BaseDatos;
use App\Services\Modelos\PacienteService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class GestorDeSurtido
{
  private SucursalService $sucursalService;
  private PacienteService $pacienteService;
  private PedidoService $pedidoService;
  private GeoLocationService $geoLocationService;
  private Collection $sinStock;
  private BaseDatos $dataBase;

  public function __construct(SucursalService $sucursalService, PacienteService $pacienteService, PedidoService $pedidoService, GeoLocationService $geoLocationService)
  {
    $this->sucursalService = $sucursalService;
    $this->pacienteService = $pacienteService;
    $this->pedidoService = $pedidoService;
    $this->geoLocationService = $geoLocationService;
    $this->sinStock = collect();
    $this->dataBase = new BaseDatos();
  }

  public function surtir(Pedido $pedido): Pedido
  {
    $this->sinStock = collect();

    $sucSeleccionada = $pedido->getSucursal();

    $ldp = $pedido->getLineasPedidos();

    foreach ($ldp as $lineaPedido) {
      /** @var LineaPedido $lineaPedido */
      $cantidadSurtida = 0;
      $ldi = $this->sucursalService->getLineaInventario($sucSeleccionada->getCadenaId(), $sucSeleccionada->getSucursalId(), $lineaPedido->getMedicamentoId());
      if (!$ldi) {
        $this->sinStock->push($lineaPedido);
        continue;
      }
      if ($ldi->hayStockDisponible()) {
        $cantidadSurtida = $ldi->cantidadPuedeSurtir($lineaPedido->getCantidad());

        $lineaPedido->crearDetalleLineaPedido($ldi->getPrecioUnitario(), $cantidadSurtida, $sucSeleccionada, $lineaPedido->getMedicamentoId());
      }
      if ($cantidadSurtida < $lineaPedido->getCantidad()) {
        $this->sinStock->push($lineaPedido);
      }
    }

    if ($this->sinStock->count() > 0) {
      // Use GeoLocationService to find nearest branches with stock using Hybrid Algorithm
      // We need to pass the list of missing medications
      $medicamentoIds = $this->sinStock->map(function ($linea) {
        return $linea->getMedicamentoId();
      })->toArray();

      $lat = $sucSeleccionada->getLatitud();
      $lng = $sucSeleccionada->getLongitud();

      // Find branches using Hybrid Algorithm (OSRM + Spatial)
      $sucCercanasWithRouteInfo = $this->geoLocationService->buscarSucursalesPorTiempoDeViaje(
        $medicamentoIds,
        $lat,
        $lng,
        $sucSeleccionada->getCadenaId(),
        $sucSeleccionada->getSucursalId()
      );

      // Extract only Sucursal objects from the enriched data
      $sucCercanasObjects = $sucCercanasWithRouteInfo->map(function ($item) {
        return $item['sucursal'];
      });

      $this->calculaFaltantes($this->sinStock, $sucCercanasObjects, $pedido);

      // Calculate optimal route for all branches involved (Source + Collection Points)
      $ruta = $pedido->getRuta();
      if ($ruta->isNotEmpty()) {
        $coordinates = [];

        $coordinates[] = [
          'lat' => $sucSeleccionada->getLatitud(),
          'lng' => $sucSeleccionada->getLongitud()
        ];

        foreach ($ruta as $sucursal) {
          $coordinates[] = [
            'lat' => $sucursal->getLatitud(),
            'lng' => $sucursal->getLongitud()
          ];
        }

        $tripDetails = $this->geoLocationService->getRoutingService()->getOptimalTrip($coordinates);

        if ($tripDetails) {
          $pedido->setRouteGeometry($tripDetails['geometry']);
        }
      }
    }

    $pedido->setFaltantes($this->sinStock);

    return $pedido;
  }

  public function calculaFaltantes(Collection $sinStock, Collection $sucCercanas, Pedido $pedido, array $stockComprometido = []): void
  {
    foreach ($sucCercanas as $sucursal) {
      /** @var Sucursal $sucursal */
      foreach ($sinStock as $ldp) {
        $this->allocateFromSucursal($sucursal, $ldp, $stockComprometido, false, null);
      }
    }
  }

  /**
   * Try allocate stock for a single line from a single sucursal.
   * If $applyUpdate is true it will decrement inventory and add route to $pedido.
   * Returns true if the line was fully satisfied and removed from sinStock.
   */
  private function allocateFromSucursal(Sucursal $sucursal, LineaPedido $ldp, array $stockComprometido = [], bool $applyUpdate = false, ?Pedido $pedido = null): bool
  {
    $ldi = $this->sucursalService->getLineaInventario($sucursal->getCadenaId(), $sucursal->getSucursalId(), $ldp->getMedicamentoId());
    if (!$ldi) {
      return false;
    }

    $cantFaltante = $ldp->getCantidadFaltante();
    if ($cantFaltante <= 0) {
      return false;
    }

    $key = $sucursal->getCadenaId() . '-' . $sucursal->getSucursalId() . '-' . $ldp->getMedicamentoId();
    $committed = $stockComprometido[$key] ?? 0;
    $stockReal = $ldi->getStockDisponible() - $committed;
    if ($stockReal <= 0) {
      return false;
    }

    if ($applyUpdate) {
      // when applying updates, use inventory's own logic to decide how much it can supply
      $cantidadSurtida = $ldi->cantidadPuedeSurtir($cantFaltante);
      // decrement and persist
      $ldi->disminuirStock($cantidadSurtida);
      $this->sucursalService->actualizarInventario($ldi);
      if ($pedido) {
        $pedido->anadirARuta($sucursal);
      }
    } else {
      $cantidadSurtida = min($cantFaltante, $stockReal);
    }

    $ldp->crearDetalleLineaPedido($ldi->getPrecioUnitario(), $cantidadSurtida, $sucursal, $ldp->getMedicamentoId());

    if ($cantidadSurtida == $cantFaltante) {
      $this->sinStock = $this->sinStock->reject(function ($item) use ($ldp) {
        return $item === $ldp;
      });
      return true;
    }

    return false;
  }

  public function confirmarPedido(Pedido $pedido): Pedido
  {
    $this->sinStock = collect();
    $this->dataBase->iniciarTransaccion();
    $ldp = $pedido->getLineasPedidos();

    try {
      // CONFIRMAR LINEAS PROMETIDAS POR EL INVENTARIO
      foreach ($ldp as $linea) {
        $detalles = $linea->getDetalles();
        foreach ($detalles as $dlp) {
          $ldi = $this->sucursalService->getLineaInventario($dlp->getSucursal()->getCadenaId(), $dlp->getSucursal()->getSucursalId(), $linea->getMedicamentoId());
          if ($ldi && $ldi->getStockDisponible() >= $dlp->getCantidadSurtida()) {
            $ldi->disminuirStock($dlp->getCantidadSurtida());
            $this->sucursalService->actualizarInventario($ldi);
            $pedido->anadirARuta($dlp->getSucursal());
          } else {
            $this->sinStock->push($linea);
            $pedido->eliminarDetalle($dlp);
          }
        }
      }

      //CALCULAR FALTANTES DE LAS LINEAS NO SURTIDAS
      if ($this->sinStock->count() > 0) {
        $sucSeleccionada = $pedido->getSucursal();

        // Use GeoLocationService to find nearest branches with stock using Hybrid Algorithm
        // This handles concurrency better - checks actual stock availability at confirmation time
        $medicamentoIds = $this->sinStock->map(function ($linea) {
          return $linea->getMedicamentoId();
        })->toArray();

        $lat = $sucSeleccionada->getLatitud();
        $lng = $sucSeleccionada->getLongitud();

        // Find branches using Hybrid Algorithm (OSRM + Spatial)
        $sucCercanasWithRouteInfo = $this->geoLocationService->buscarSucursalesPorTiempoDeViaje(
          $medicamentoIds,
          $lat,
          $lng,
          $sucSeleccionada->getCadenaId(),
          $sucSeleccionada->getSucursalId()
        );

        // Extract only Sucursal objects from the enriched data
        $sucCercanas = $sucCercanasWithRouteInfo->map(function ($item) {
          return $item['sucursal'];
        });

        $this->calculaFaltantesWithUpdate($this->sinStock, $sucCercanas, $pedido);
      }

      if ($pedido->calcularPorcentajeSurtido() < 0.5) {
        $pedido->setFaltantes($this->sinStock);
        throw new \RuntimeException('No se pudo surtir al menos el 50% del pedido.');
      }

      $pedido->removerLineasSinDetalles();
      $pedido->setEstatus(PedidoModel::ESTATUS_CONFIRMADO);
      $pedido->calcularTotales();

      // Calculate optimal route for all branches involved (Source + Collection Points)
      $ruta = $pedido->getRuta();
      if ($ruta->isNotEmpty()) {
        $sucSeleccionada = $pedido->getSucursal();
        $coordinates = [];

        $coordinates[] = [
          'lat' => $sucSeleccionada->getLatitud(),
          'lng' => $sucSeleccionada->getLongitud()
        ];

        foreach ($ruta as $sucursal) {
          $coordinates[] = [
            'lat' => $sucursal->getLatitud(),
            'lng' => $sucursal->getLongitud()
          ];
        }

        $tripDetails = $this->geoLocationService->getRoutingService()->getOptimalTrip($coordinates);

        if ($tripDetails) {
          $pedido->setRouteGeometry($tripDetails['geometry']);
        }
      }

      $montoPenalizacion = $this->pacienteService->getMontoPenalizacion($pedido->getPacienteId());
      $pedido->sumarMontoPenalizacion((float) $montoPenalizacion);

      $this->guardarPedido($pedido);
      $this->dataBase->commitTransaccion();
      $pedido->setFaltantes($this->sinStock);
      return $pedido;
    } catch (\Throwable $e) {
      $this->dataBase->cancelarTransaccion();
      throw $e;
    }
  }

  private function calculaFaltantesWithUpdate(Collection $sinStock, Collection $sucCercanas, Pedido $pedido): void
  {
    foreach ($sucCercanas as $suc) {
      foreach ($sinStock as $ldp) {
        // Try to allocate and apply updates (decrement inventory, add route)
        $this->allocateFromSucursal($suc, $ldp, [], true, $pedido);
      }
    }
  }


  private function guardarPedido(Pedido $pedido): Pedido
  {
    DB::transaction(function () use ($pedido) {

      $pedidoBD = $this->dataBase->guardarPedido($pedido);

      $folioPedido = $pedidoBD->folio_pedido;
      $this->notificarSucursalesParticipantes($pedido->getRuta(), $folioPedido);

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
      if ($pedido->getMontoPenalizacion() > 0) {
        $this->dataBase->guardarMontoPenalizacion($pedido->getFolio(), $pedido->getMontoPenalizacion());
      }
    });

    return $pedido;
  }
  private function notificarSucursalesParticipantes(Collection $ruta, string $folioPedido): void
  {
    foreach ($ruta as $sucursal) {
      $this->sucursalService->notificarNuevoPedido($sucursal, $folioPedido);
    }

  }

}
