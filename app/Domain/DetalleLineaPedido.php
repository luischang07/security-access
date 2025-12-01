<?php

namespace App\Domain;

use App\Domain\Sucursal;

class DetalleLineaPedido
{
  private float $precio;
  private int $cantidadSurtida;
  private Sucursal $sucSeleccionada;
  private int $medicamentoId;

  public function __construct(float $precio, int $cantidadSurtida, Sucursal $sucSeleccionada, int $medicamentoId)
  {
    $this->precio = $precio;
    $this->cantidadSurtida = $cantidadSurtida;
    $this->sucSeleccionada = $sucSeleccionada;
    $this->medicamentoId = $medicamentoId;
  }

  public function calcularSubtotalDetalle(): float
  {
    return $this->precio * $this->cantidadSurtida;
  }

  public function getCantidadSurtida(): int
  {
    return $this->cantidadSurtida;
  }
  public function getSucursal(): Sucursal
  {
    return $this->sucSeleccionada;
  }
  public function getPrecio(): float
  {
    return $this->precio;
  }

  public function getMedicamentoId(): int
  {
    return $this->medicamentoId;
  }
}
