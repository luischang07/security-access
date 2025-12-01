<?php

namespace App\Services\Modelos;

use App\Domain\LineaInventario;
use App\Repositories\BaseDatos;
use App\Domain\Sucursal;
use Illuminate\Support\Collection;

class SucursalService
{
  private BaseDatos $dataBase;

  public function __construct()
  {
    $this->dataBase = new BaseDatos();
  }

  public function getTodasSucursales(): Collection
  {
    return $this->dataBase->getTodasSucursales();
  }

  public function getSucursal($cadena_id, $sucursal_id): Sucursal
  {
    return $this->dataBase->getSucursal($cadena_id, $sucursal_id);
  }

  public function getLineaInventario($cadena_id, $sucursal_id, $medicamento_id): LineaInventario
  {
    return $this->dataBase->getInventario($cadena_id, $sucursal_id, $medicamento_id);
  }

  public function actualizarInventario($ldi): void
  {
    $this->dataBase->actualizarInventario($ldi);
  }
}
