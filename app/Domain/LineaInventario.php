<?php


namespace App\Domain;

class LineaInventario
{
  private string $cadenaId;
  private string $sucursalId;
  private int $medicamentoId;
  private int $stockDisponible;
  private float $precioUnitario;

  public function __construct(string $cadenaId, string $sucursalId, int $medicamentoId, int $stockDisponible, float $precioUnitario)
  {
    $this->cadenaId = $cadenaId;
    $this->sucursalId = $sucursalId;
    $this->medicamentoId = $medicamentoId;
    $this->stockDisponible = $stockDisponible;
    $this->precioUnitario = $precioUnitario;
  }

  public function getMedicamentoId(): int
  {
    return $this->medicamentoId;
  }

  public function getStockDisponible(): int
  {
    return $this->stockDisponible;
  }

  public function setCantidad(int $cantidad): void
  {
    $this->stockDisponible = $cantidad;
  }

  public function setMedicamentoId(int $medicamentoId): void
  {
    $this->medicamentoId = $medicamentoId;
  }

  public function setStockDisponible(int $stockDisponible): void
  {
    $this->stockDisponible = $stockDisponible;
  }

  public function getPrecioUnitario(): float
  {
    return $this->precioUnitario;
  }

  public function setPrecioUnitario(float $precioUnitario): void
  {
    $this->precioUnitario = $precioUnitario;
  }

  public function disminuirStock(int $cantidad): void
  {
    $this->stockDisponible -= $cantidad;
  }

  public function aumentarStock(int $cantidad): void
  {
    $this->stockDisponible += $cantidad;
  }

  public function hayStockDisponible(): bool
  {
    return $this->stockDisponible > 0;
  }

  public function cantidadPuedeSurtir(int $cantidadSolicitada): int
  {
    return $this->stockDisponible >= $cantidadSolicitada ? $cantidadSolicitada : $this->stockDisponible;
  }

  public function getCadenaId(): string
  {
    return $this->cadenaId;
  }

  public function getSucursalId(): string
  {
    return $this->sucursalId;
  }
}
