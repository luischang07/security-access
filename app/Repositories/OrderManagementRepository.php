<?php

namespace App\Repositories;

use App\Models\Pedido;
use App\Models\CadenaFarmaceutica;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class OrderManagementRepository
{
  /**
   * Get paginated orders with filters
   */
  public function getPaginatedOrders(int $perPage = 10, array $filters = []): LengthAwarePaginator
  {
    $query = Pedido::with(['paciente.user', 'lineasPedidos']);

    // Search filter (Order ID or Patient Name)
    if (!empty($filters['search'])) {
      $search = $filters['search'];
      $query->where(function ($q) use ($search) {
        $q->where('pedido_id', 'like', "%{$search}%")
          ->orWhereHas('paciente.user', function ($q) use ($search) {
            $q->where('nombre', 'like', "%{$search}%")
              ->orWhere('apellido', 'like', "%{$search}%");
          });
      });
    }

    // Status filter
    if (!empty($filters['status'])) {
      $query->where('estatus', $filters['status']);
    }

    // Pharmacy filter (Chain ID)
    if (!empty($filters['pharmacy'])) {
      $query->where('cadena_id', $filters['pharmacy']);
    }

    // Date filter
    if (!empty($filters['date'])) {
      switch ($filters['date']) {
        case 'today':
          $query->whereDate('fecha_pedido', now());
          break;
        case 'week':
          $query->whereBetween('fecha_pedido', [now()->startOfWeek(), now()->endOfWeek()]);
          break;
        case 'month':
          $query->whereMonth('fecha_pedido', now()->month)
            ->whereYear('fecha_pedido', now()->year);
          break;
      }
    }

    // Default sorting
    $query->orderBy('fecha_pedido', 'desc');

    return $query->paginate($perPage);
  }

  /**
   * Get order statistics
   */
  public function getOrderStats(): array
  {
    $totalOrders = Pedido::count();
    $pendingOrders = Pedido::where('estatus', 'pendiente')->count();
    $completedToday = Pedido::where('estatus', Pedido::ESTATUS_COMPLETADO)
      ->whereDate('fecha_recoleccion', now())
      ->count();

    // Calculate average fulfillment time in hours
    $avgFulfillmentHours = Pedido::whereNotNull('fecha_recoleccion')
      ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, fecha_pedido, fecha_recoleccion)) as avg_hours')
      ->value('avg_hours') ?? 0;

    return [
      'total_orders' => $totalOrders,
      'pending_orders' => $pendingOrders,
      'completed_today' => $completedToday,
      'avg_fulfillment_hours' => round($avgFulfillmentHours, 1),
    ];
  }

  /**
   * Get all pharmacy chains for filter
   */
  public function getAllChains(): array
  {
    return CadenaFarmaceutica::pluck('name', 'cadena_id')->toArray();
  }
}
