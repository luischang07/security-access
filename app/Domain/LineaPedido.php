<?php

namespace App\Domain;

use App\Domain\DetalleLineaPedido;
use App\Domain\Medicamento;
use App\Domain\Sucursal;
use Illuminate\Support\Collection;

class LineaPedido
{
  private int $cantidad;
  private int $medicamentoId;
  private ?Medicamento $medicamento;
  /** @var \Illuminate\Support\Collection|DetalleLineaPedido[] */
  private Collection $detalleLineaPedido;

  public function __construct(int $medicamentoId, int $cantidad, ?Medicamento $medicamento)
  {
    $this->medicamentoId = $medicamentoId;
    $this->cantidad = $cantidad;
    $this->medicamento = $medicamento;
    $this->detalleLineaPedido = collect();
  }

  public function getCantidad(): int
  {
    return $this->cantidad;
  }
  public function getMedicamentoId(): int
  {
    return $this->medicamentoId;
  }

  public function getMedicamento(): ?Medicamento
  {
    return $this->medicamento;
  }

  public function setCantidad(int $cantidad): void
  {
    $this->cantidad = $cantidad;
  }
  public function setMedicamentoId(int $medicamentoId): void
  {
    $this->medicamentoId = $medicamentoId;
  }

  // public function setStockDisponible(int $stockDisponible): void
  // {
  //   // kept for compatibility; not used in domain model
  //   $this->stockDisponible = $stockDisponible;
  // }

  //Crear detalle linea de pedido
  public function crearDetalleLineaPedido(float $precio, int $cantidadSurtida, Sucursal $sucSeleccionada, int $medId): void
  {
    $existeDetalle = $this->existeDetalle($sucSeleccionada, $medId);

    if ($existeDetalle) {
      $existeDetalle->aumentarCantidad($cantidadSurtida);
    } else {
      $dlp = new DetalleLineaPedido($precio, $cantidadSurtida, $sucSeleccionada, $medId);
      $this->detalleLineaPedido->push($dlp);
    }
  }

  private function existeDetalle($sucSeleccionada, $medId)
  {
    return $this->detalleLineaPedido->first(function (DetalleLineaPedido $detalle) use ($sucSeleccionada, $medId) {
      return $detalle->getSucursal()->getCadenaId() === $sucSeleccionada->getCadenaId() &&
        $detalle->getSucursal()->getSucursalId() === $sucSeleccionada->getSucursalId() &&
        $detalle->getMedicamentoId() === $medId;
    });
  }

  public function getCantidadFaltante(): int
  {
    $faltante = $this->cantidad;
    foreach ($this->detalleLineaPedido as $detalle) {
      $faltante -= $detalle->getCantidadSurtida();
    }
    return $faltante;
  }

  public function getDetalleLineaPedido(): Collection
  {
    return $this->detalleLineaPedido;
  }

  /** Compatibility alias used across views/services */
  public function getDetalles(): Collection
  {
    return $this->getDetalleLineaPedido();
  }

  public function eliminarDetalle(DetalleLineaPedido $dlp): void
  {
    if (!$this->detalleLineaPedido instanceof Collection) {
      return;
    }

    $this->detalleLineaPedido = $this->detalleLineaPedido
      ->filter(function (DetalleLineaPedido $detalle) use ($dlp) {
        return !(
          $detalle->getSucursal()->getCadenaId() === $dlp->getSucursal()->getCadenaId() &&
          $detalle->getSucursal()->getSucursalId() === $dlp->getSucursal()->getSucursalId() &&
          $detalle->getMedicamentoId() === $dlp->getMedicamentoId()
        );
      })
      ->values();
  }

  public function limpiarDetalles(): void
  {
    $this->detalleLineaPedido = collect();
  }

  public function calcularSubtotal(): float
  {
    $subtotal = 0;
    foreach ($this->detalleLineaPedido as $detalle) {
      $subtotal += $detalle->calcularSubtotalDetalle();
    }
    return $subtotal;
  }

  public function calcularCantidadSurtida(): int
  {
    $cantidadSurtida = 0;
    foreach ($this->detalleLineaPedido as $detalle) {
      $cantidadSurtida += $detalle->getCantidadSurtida();
    }
    return $cantidadSurtida;
  }
}
