<?php

namespace App\Repositories;

use App\Models\Sucursal;
use App\Models\Pedido;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PharmacyManagementRepository
{
  /**
   * Get paginated pharmacies with filters
   */
  public function getPaginatedPharmacies(int $perPage = 15, array $filters = []): LengthAwarePaginator
  {
    // Build subquery for order counts
    $orderCountsSubquery = DB::table('pedidos')
      ->select(
        'cadena_id',
        'sucursal_id',
        DB::raw('COUNT(DISTINCT folio_pedido) as total_orders')
      )
      ->groupBy('cadena_id', 'sucursal_id');

    // Main query using DB query builder to avoid composite key issues
    $query = DB::table('sucursales')
      ->leftJoinSub($orderCountsSubquery, 'order_counts', function ($join) {
        $join->on('sucursales.cadena_id', '=', 'order_counts.cadena_id')
          ->on('sucursales.sucursal_id', '=', 'order_counts.sucursal_id');
      })
      ->leftJoin('cadena_farmaceuticas', 'sucursales.cadena_id', '=', 'cadena_farmaceuticas.cadena_id')
      ->select(
        DB::raw('CONCAT(sucursales.cadena_id, "-", sucursales.sucursal_id) as id'),
        'sucursales.cadena_id',
        'sucursales.sucursal_id',
        'sucursales.nombre',
        'sucursales.calle',
        'sucursales.numero_ext',
        'sucursales.numero_int',
        'sucursales.colonia',
        'sucursales.latitud',
        'sucursales.longitud',
        'cadena_farmaceuticas.name as cadena_name',
        DB::raw('COALESCE(order_counts.total_orders, 0) as total_orders')
      )
      ->orderBy('sucursales.cadena_id')
      ->orderBy('sucursales.sucursal_id');

    // Search filter
    if (!empty($filters['search'])) {
      $search = $filters['search'];
      $query->where(function ($q) use ($search) {
        $q->where('sucursales.nombre', 'like', "%{$search}%")
          ->orWhere('sucursales.calle', 'like', "%{$search}%")
          ->orWhere('sucursales.colonia', 'like', "%{$search}%")
          ->orWhere('sucursales.sucursal_id', 'like', "%{$search}%");
      });
    }

    // Chain filter
    if (!empty($filters['chain'])) {
      $query->where('sucursales.cadena_id', $filters['chain']);
    }

    // Status filter
    if (!empty($filters['status'])) {
      if ($filters['status'] === 'active') {
        $query->havingRaw('total_orders > 0');
      } elseif ($filters['status'] === 'inactive') {
        $query->havingRaw('total_orders = 0');
      }
    }

    return $query->paginate($perPage);
  }

  /**
   * Get pharmacy statistics
   */
  public function getPharmacyStats(): array
  {
    $totalPharmacies = Sucursal::count();

    // Count pharmacies that have at least one pedido
    $activePharmacies = DB::table('sucursales')
      ->join('pedidos', function ($join) {
        $join->on('sucursales.cadena_id', '=', 'pedidos.cadena_id')
          ->on('sucursales.sucursal_id', '=', 'pedidos.sucursal_id');
      })
      ->distinct()
      ->count(DB::raw('CONCAT(sucursales.cadena_id, "-", sucursales.sucursal_id)'));

    // Calculate average days to delivery
    $avgFulfillmentDays = Pedido::whereNotNull('fecha_entrega')
      ->selectRaw('AVG(DATEDIFF(fecha_entrega, fecha_pedido)) as avg_days')
      ->value('avg_days') ?? 0;

    return [
      'total_pharmacies' => $totalPharmacies,
      'active_pharmacies' => $activePharmacies,
      'avg_rating' => 4.5, // Placeholder value
      'avg_fulfillment_hours' => round($avgFulfillmentDays * 24, 1),
    ];
  }

  /**
   * Get all chains for filter dropdown
   */
  public function getAllChains(): array
  {
    return \App\Models\CadenaFarmaceutica::all()
      ->pluck('name', 'cadena_id')
      ->toArray();
  }

  /**
   * Get pharmacy details by composite key
   */
  public function getPharmacyDetails(string $cadenaId, string $sucursalId): ?Sucursal
  {
    return Sucursal::where('cadena_id', $cadenaId)
      ->where('sucursal_id', $sucursalId)
      ->with(['cadena', 'empleados', 'pedidos'])
      ->first();
  }

  /**
   * Delete pharmacy
   */
  public function deletePharmacy(string $cadenaId, string $sucursalId): bool
  {
    $pharmacy = Sucursal::where('cadena_id', $cadenaId)
      ->where('sucursal_id', $sucursalId)
      ->first();

    return $pharmacy ? $pharmacy->delete() : false;
  }

  /**
   * Get monthly orders for a pharmacy
   */
  public function getMonthlyOrders(string $cadenaId, string $sucursalId): int
  {
    return Pedido::where('cadena_id', $cadenaId)
      ->where('sucursal_id', $sucursalId)
      ->whereMonth('fecha_pedido', now()->month)
      ->whereYear('fecha_pedido', now()->year)
      ->count();
  }
}
