<?php

namespace App\Services\Modelos;

use App\Domain\LineaInventario;
use App\Repositories\BaseDatos;
use App\Domain\Sucursal;
use Illuminate\Support\Collection;
use App\Domain\Notificacion;

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

  public function getSucursal(string $cadena_id, string $sucursal_id): ?Sucursal
  {
    return $this->dataBase->getSucursal($cadena_id, $sucursal_id);
  }

  public function getSucursalesPorCadena(string $cadena_id): Collection
  {
    return $this->dataBase->getSucursalesPorCadena($cadena_id);
  }

  public function getLineaInventario(string $cadena_id, string $sucursal_id, int $medicamento_id): ?LineaInventario
  {
    return $this->dataBase->getInventario($cadena_id, $sucursal_id, $medicamento_id);
  }

  public function actualizarInventario(LineaInventario $ldi): void
  {
    $this->dataBase->actualizarInventario($ldi);
  }
  public function notificarNuevoPedido(Sucursal $sucursal, $folioPedido)
  {
    $notificacion = Notificacion::crear("Se ha asignado nuevo pedido: " . $folioPedido, now(), false);
    $sucursal->agregarNotificacion($notificacion);
    $this->dataBase->guardarNotificacionSucursal($sucursal, $folioPedido, $notificacion);
  }
}
