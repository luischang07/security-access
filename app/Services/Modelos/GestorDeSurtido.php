<?php

namespace App\Services\Modelos;
use App\Domain\Pedido;
use App\Domain\Sucursal;
use App\Domain\DetalleLineaPedido;
use App\Domain\LineaPedido;
use App\Domain\LineaInventario;
use App\Services\Modelos\PedidoService;
use App\Services\Modelos\SucursalService;
use App\Services\GeoLocationService;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Collection;
use App\Repositories\BaseDatos;
use DB;


class GestorDeSurtido
{
  private SucursalService $sucursalService;
  private PedidoService $pedidoService;
  private GeoLocationService $geoLocationService;
  private $SinStock;
  private BaseDatos $dataBase;

  public function __construct(SucursalService $sucursalService, PedidoService $pedidoService, GeoLocationService $geoLocationService)
  {
    $this->sucursalService = $sucursalService;
    $this->pedidoService = $pedidoService;
    $this->geoLocationService = $geoLocationService;
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
      // Use GeoLocationService to find nearest branches with stock
      // We need to pass the list of missing medications
      $medicamentoIds = $this->SinStock->map(function ($linea) {
        return $linea->getMedicamentoId();
      })->toArray();

      // Assuming sucursal has location, otherwise fallback to lat/long
      // For now we pass lat/long as fallback or primary if location not yet migrated
      $lat = $sucsel->getLatitud(); // Ensure these getters exist or use direct access if model
      $lng = $sucsel->getLongitud();

      // Find branches excluding the current one
      // Note: We need to handle the case where we need multiple branches for different meds
      // The service returns a list of branches that have *some* of the meds.
      // We might need to iterate or call it per medication if we want specific optimization per item,
      // but the requirement says "nearest branches".

      // Let's get a pool of nearest branches that have ANY of the missing items
      $sucCercanas = $this->geoLocationService->findNearestBranchesWithStock(
        $medicamentoIds,
        $lat,
        $lng,
        $sucsel->getSucursalId()
      );

      // Convert stdClass results from DB to Domain\Sucursal objects if needed
      // The service returns DB rows. We need to convert them.
      $sucCercanasObjects = collect();
      foreach ($sucCercanas as $sucRow) {
        // We need a way to hydrate the domain object. 
        // Assuming SucursalService or BaseDatos has a way, or we manually map.
        // For now, let's use the existing sucursalService->obtenerSucursal logic or similar
        // But that might be N+1. Better to hydrate from row.
        // Let's assume we can create it.
        // DomainSucursal::crear expects a Model.
        $sucModel = new \App\Models\Sucursal((array) $sucRow);
        $sucCercanasObjects->push(\App\Domain\Sucursal::crear($sucModel));
      }

      $this->CalculaFaltantes($this->SinStock, $sucCercanasObjects, $pedido);
    }
    return $pedido;
  }

  public function CalculaFaltantes($SinStock, $sucCercanas, $pedido, $stockComprometido = [])
  {

    foreach ($sucCercanas as $suc) {
      foreach ($SinStock as $ldp) {
        $ldi = $this->sucursalService->getLineaInventario($suc->getCadenaId(), $suc->getSucursalId(), $ldp->getMedicamentoId());
        $cantFaltante = $ldp->getCantidadFaltante();
        $cantidadSurtida = 0;

        // Check committed stock if passed
        $key = $suc->getCadenaId() . '-' . $suc->getSucursalId() . '-' . $ldp->getMedicamentoId();
        $committed = $stockComprometido[$key] ?? 0;
        $stockReal = $ldi->getStockDisponible() - $committed;

        if ($stockReal > 0 && $cantFaltante > 0) {
          $cantidadSurtida = $ldp->getCantidad() - $stockReal <= 0 ? $ldp->getCantidad() : $stockReal;

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
        info("detalles");
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
    return DB::transaction(function () use ($pedido) {

      $pedidoBD = $this->dataBase->guardarPedido($pedido);

      $folioPedido = $pedidoBD->folio_pedido;
      foreach ($pedido->getLineasPedido() as $lineaPedido) {
        info("Guardando linea de pedido", [$lineaPedido]);
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

      return $pedidoBD;
    });
  }




  public function confirmarPedidoFake($pedido)
  {
    $this->SinStock = collect();
    $lineas = $pedido->getLineasPedidos();
    $stockComprometido = [];

    foreach ($lineas as $linea) {
      $detalles = $linea->getDetalles();
      $faltante = $linea->getCantidad();
      $detallesActualizados = collect();

      foreach ($detalles as $detalle) {
        $inv = $this->sucursalService->getLineaInventario(
          $detalle->getSucursal()->getCadenaId(),
          $detalle->getSucursal()->getSucursalId(),
          $detalle->getMedicamentoId()
        );

        $disponible = $inv ? $inv->getStockDisponible() : 0;
        $cantidadSurtible = min($disponible, $detalle->getCantidadSurtida());

        if ($cantidadSurtible > 0) {
          $detallesActualizados->push(
            new DetalleLineaPedido($inv->getPrecioUnitario(), $cantidadSurtible, $detalle->getSucursal(), $detalle->getMedicamentoId())
          );
          $key = $detalle->getSucursal()->getCadenaId() . '-' . $detalle->getSucursal()->getSucursalId() . '-' . $detalle->getMedicamentoId();
          $stockComprometido[$key] = ($stockComprometido[$key] ?? 0) + $cantidadSurtible;
          $faltante -= $cantidadSurtible;
        }
      }

      // Reemplazar detalles con las cantidades efectivamente disponibles
      $detalles->splice(0);
      foreach ($detallesActualizados as $detalleNuevo) {
        $detalles->push($detalleNuevo);
      }

      //No se encontro completamente el medicamento
      if ($faltante > 0) {
        $this->SinStock->push($linea);
      }
    }

    if ($this->SinStock->count() > 0) {
      $sucursalBase = $pedido->getSucursal();
      $sucursalesBusqueda = collect([$sucursalBase])
        ->merge($this->sucursalService->calculaSucCercanas($sucursalBase->getCadenaId(), $sucursalBase->getSucursalId()));

      $this->CalculaFaltantes($this->SinStock, $sucursalesBusqueda, $pedido, $stockComprometido);
    }

    if ($this->SinStock->count() > 0) {
      return [
        'ok' => false,
        'faltantes' => $this->SinStock,
        'pedido' => $pedido,
      ];
    }

    $pedidoGuardado = $this->guardarPedido($pedido);

    return [
      'ok' => true,
      'folio' => $pedidoGuardado->folio_pedido,
      'pedido' => $pedidoGuardado,
    ];
  }

}