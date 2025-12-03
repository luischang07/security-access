<?php

namespace App\Services;

use App\Repositories\PacienteRepository;
use App\Repositories\PedidoRepository;
use App\Repositories\SucursalRepository;
use Illuminate\Support\Facades\DB;

class AdminDashboardService
{
  protected PacienteRepository $pacienteRepository;
  protected SucursalRepository $sucursalRepository;
  protected PedidoRepository $pedidoRepository;

  public function __construct(
    PacienteRepository $pacienteRepository,
    SucursalRepository $sucursalRepository,
    PedidoRepository $pedidoRepository
  ) {
    $this->pacienteRepository = $pacienteRepository;
    $this->sucursalRepository = $sucursalRepository;
    $this->pedidoRepository = $pedidoRepository;
  }

  /**
   * Get dashboard statistics
   *
   * @return array
   */
  public function getDashboardStats(): array
  {
    return [
      'total_patients' => $this->pacienteRepository->getTotalCount(),
      'patients_growth' => $this->pacienteRepository->getGrowthPercentage(),
      'active_pharmacies' => $this->sucursalRepository->getActiveCount(),
      'pharmacies_growth' => $this->sucursalRepository->getGrowthPercentage(),
      'prescriptions_today' => $this->getPrescriptionsToday(),
      'prescriptions_growth' => $this->getPrescriptionsGrowth(),
      'avg_fulfillment_time' => $this->getAverageFulfillmentTime(),
      'fulfillment_trend' => $this->getFulfillmentTrend(),
    ];
  }

  /**
   * Get new registrations for the last 30 days
   *
   * @return array
   */
  public function getNewRegistrations(): array
  {
    $registrations = $this->pacienteRepository->getNewRegistrationsLastDays(30);
    $total = array_sum(array_column($registrations, 'count'));

    // Calculate growth
    $halfPoint = count($registrations) / 2;
    $firstHalf = array_slice($registrations, 0, (int) $halfPoint);
    $secondHalf = array_slice($registrations, (int) $halfPoint);

    $firstHalfTotal = array_sum(array_column($firstHalf, 'count'));
    $secondHalfTotal = array_sum(array_column($secondHalf, 'count'));

    $growth = $firstHalfTotal > 0
      ? round((($secondHalfTotal - $firstHalfTotal) / $firstHalfTotal) * 100, 1)
      : 0;

    return [
      'total' => $total,
      'growth' => $growth,
      'data' => $registrations,
    ];
  }

  /**
   * Get recent activity
   *
   * @param int $limit
   * @return array
   */
  public function getRecentActivity(int $limit = 5): array
  {
    // This would normally fetch from an activity log table
    // For now, we'll return a placeholder structure
    return [
      // Implementation depends on if you have an activity log table
    ];
  }

  /**
   * Get prescriptions processed today
   *
   * @return int
   */
  protected function getPrescriptionsToday(): int
  {
    // Using pedidos where fecha_pedido is today
    return \App\Models\Pedido::whereDate('fecha_pedido', today())->count();
  }

  /**
   * Get prescriptions growth percentage
   *
   * @return float
   */
  protected function getPrescriptionsGrowth(): float
  {
    $today = \App\Models\Pedido::whereDate('fecha_pedido', today())->count();
    $yesterday = \App\Models\Pedido::whereDate('fecha_pedido', today()->subDay())->count();

    if ($yesterday === 0) {
      return $today > 0 ? 100.0 : 0.0;
    }

    return round((($today - $yesterday) / $yesterday) * 100, 1);
  }

  /**
   * Get average fulfillment time in hours
   *
   * @return float
   */
  protected function getAverageFulfillmentTime(): float
  {
    $pedidos = \App\Models\Pedido::whereNotNull('fecha_recoleccion')
      ->where('estatus', 'completado')
      ->whereDate('fecha_recoleccion', '>=', now()->subDays(30))
      ->whereDate('fecha_recoleccion', '>=', now()->subDays(30))
      ->selectRaw('AVG(' . $this->getDiffInHoursSql('fecha_pedido', 'fecha_recoleccion') . ') as avg_hours')
      ->first();

    return $pedidos->avg_hours ? round($pedidos->avg_hours, 1) : 0.0;
  }

  /**
   * Get fulfillment time trend (positive or negative)
   *
   * @return float
   */
  protected function getFulfillmentTrend(): float
  {
    $currentWeek = \App\Models\Pedido::whereNotNull('fecha_recoleccion')
      ->where('estatus', 'completado')
      ->whereDate('fecha_recoleccion', '>=', now()->subWeek())
      ->whereDate('fecha_recoleccion', '>=', now()->subWeek())
      ->selectRaw('AVG(' . $this->getDiffInHoursSql('fecha_pedido', 'fecha_recoleccion') . ') as avg_hours')
      ->first();

    $lastWeek = \App\Models\Pedido::whereNotNull('fecha_recoleccion')
      ->where('estatus', 'completado')
      ->whereDate('fecha_recoleccion', '>=', now()->subWeeks(2))
      ->whereDate('fecha_recoleccion', '<', now()->subWeek())
      ->whereDate('fecha_recoleccion', '<', now()->subWeek())
      ->selectRaw('AVG(' . $this->getDiffInHoursSql('fecha_pedido', 'fecha_recoleccion') . ') as avg_hours')
      ->first();

    $current = $currentWeek->avg_hours ?? 0;
    $last = $lastWeek->avg_hours ?? 0;

    if ($last === 0) {
      return 0.0;
    }

    return round((($current - $last) / $last) * 100, 1);
  }

  /**
   * Get SQL for difference in hours based on database driver
   */
  private function getDiffInHoursSql(string $startColumn, string $endColumn): string
  {
    $driver = DB::connection()->getDriverName();

    return match ($driver) {
      'pgsql' => "EXTRACT(EPOCH FROM ($endColumn - $startColumn)) / 3600",
      'sqlite' => "(julianday($endColumn) - julianday($startColumn)) * 24",
      default => "TIMESTAMPDIFF(HOUR, $startColumn, $endColumn)", // MySQL and others
    };
  }
}
