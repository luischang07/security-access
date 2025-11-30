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

  /**
   * Obtener cantidad de pedidos completados de un paciente
   *
   * @param int $patientId
   * @return int
   */
  public function getCompletedOrdersCount(int $patientId): int
  {
    return Pedido::forPatient($patientId)
      ->where('estatus', 'entregado')
      ->count();
  }

  /**
   * Obtener cantidad de pedidos cancelados de un paciente
   *
   * @param int $patientId
   * @return int
   */
  public function getCancelledOrdersCount(int $patientId): int
  {
    return Pedido::forPatient($patientId)
      ->where('estatus', 'cancelado')
      ->count();
  }

  /**
   * Obtener historial reciente de pedidos (entregados o cancelados)
   *
   * @param int $patientId
   * @param int $limit
   * @return Collection
   */
  public function getRecentHistory(int $patientId, int $limit = 3): Collection
  {
    return Pedido::forPatient($patientId)
      ->whereIn('estatus', ['entregado', 'cancelado'])
      ->latest('fecha_pedido')
      ->take($limit)
      ->get();
  }
  /**
   * Crear un nuevo pedido con sus líneas
   *
   * @param array $data
   * @param array $medications
   * @return Pedido
   */
  public function createOrder(array $data, array $medications): Pedido
  {
    return \DB::transaction(function () use ($data, $medications) {
      // Generar folio único
      $folio = \Illuminate\Support\Str::uuid()->toString();

      // Crear el pedido
      $pedido = Pedido::create([
        'folio_pedido' => $folio,
        'paciente_id' => $data['paciente_id'],
        'cadena_id' => $data['cadena_id'],
        'sucursal_id' => $data['sucursal_id'],
        'cedula_profesional' => $data['cedula_profesional'],
        'fecha_pedido' => now(),
        'estatus' => 'pendiente',
        'costo_total' => 0,
        'route_geometry' => $data['route_geometry'] ?? null,
      ]);

      // Crear líneas de pedido
      foreach ($medications as $index => $medication) {
        $medicationId = $medication['medication_id'] ?? $medication['id'] ?? null;
        if (!$medicationId && !empty($medication['name'])) {
          $medModel = \App\Models\Medicamento::where('nombre', 'LIKE', '%' . $medication['name'] . '%')->first();
          $medicationId = $medModel?->id;
        }

        if ($medicationId) {
          \App\Models\LineaPedido::create([
            'folio_pedido' => $folio,
            'id_linea_pedido' => $index + 1,
            'medicamento_id' => $medicationId,
            'cantidad' => $medication['quantity'],
          ]);
        }
      }

      return $pedido;
    });
  }
}
