<?php

namespace App\Repositories;

use App\Models\Pedido;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class PedidoRepository
{
  /**
   * Obtener pedidos activos de un paciente
   *
   * @param int $patientId
   * @param int $limit
   * @return Collection
   */
  public function getActiveOrdersForPatient(int $patientId, int $limit = 5): Collection
  {
    return Pedido::forPatient($patientId)
      ->whereIn('estatus', ['pendiente', 'en_proceso'])
      ->with(['lineasPedidos'])
      ->latest('fecha_pedido')
      ->take($limit)
      ->get();
  }

  /**
   * Obtener todos los pedidos de un paciente con paginación
   *
   * @param int $patientId
   * @param int $perPage
   * @return LengthAwarePaginator
   */
  public function getPaginatedOrdersForPatient(int $patientId, int $perPage = 10): LengthAwarePaginator
  {
    return Pedido::forPatient($patientId)
      ->with(['lineasPedidos'])
      ->latest('fecha_pedido')
      ->paginate($perPage);
  }

  /**
   * Obtener historial de pedidos de un paciente (entregados o cancelados)
   *
   * @param int $patientId
   * @param int $perPage
   * @return LengthAwarePaginator
   */
  public function getOrderHistoryForPatient(int $patientId, int $perPage = 15): LengthAwarePaginator
  {
    return Pedido::forPatient($patientId)
      ->whereIn('estatus', ['entregado', 'cancelado'])
      ->with(['lineasPedidos'])
      ->latest('fecha_pedido')
      ->paginate($perPage);
  }

  /**
   * Obtener pedidos pendientes de una sucursal
   *
   * @param string $cadenaId
   * @param string $sucursalId
   * @param int $limit
   * @return Collection
   */
  public function getPendingOrdersForBranch(string $cadenaId, string $sucursalId, int $limit = 10): Collection
  {
    return Pedido::where('cadena_id', $cadenaId)
      ->where('sucursal_id', $sucursalId)
      ->whereIn('estatus', ['pendiente', 'en_proceso'])
      ->with(['paciente.user', 'lineasPedidos'])
      ->latest('fecha_pedido')
      ->take($limit)
      ->get();
  }

  /**
   * Obtener pedidos de una sucursal con paginación
   *
   * @param string $cadenaId
   * @param string $sucursalId
   * @param int $perPage
   * @return LengthAwarePaginator
   */
  public function getPaginatedOrdersForBranch(string $cadenaId, string $sucursalId, int $perPage = 20): LengthAwarePaginator
  {
    return Pedido::where('cadena_id', $cadenaId)
      ->where('sucursal_id', $sucursalId)
      ->with(['paciente.user', 'lineasPedidos'])
      ->latest('fecha_pedido')
      ->paginate($perPage);
  }
}
