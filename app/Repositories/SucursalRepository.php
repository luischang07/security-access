<?php

namespace App\Repositories;

use App\Models\Sucursal;
use Illuminate\Support\Facades\DB;

class SucursalRepository
{
  /**
   * Get total count of pharmacies (sucursales)
   *
   * @return int
   */
  public function getTotalCount(): int
  {
    return Sucursal::count();
  }

  /**
   * Get count of active pharmacies
   * (For now, all are considered active as there's no status field)
   *
   * @return int
   */
  public function getActiveCount(): int
  {
    return Sucursal::count();
  }

  /**
   * Get pharmacy growth percentage
   *
   * @return float
   */
  public function getGrowthPercentage(): float
  {
    $currentTotal = $this->getTotalCount();

    // Calculate the date one month ago
    $oneMonthAgo = now()->subMonth();

    // Get the count of sucursales that existed one month ago
    $totalOneMonthAgo = Sucursal::where('created_at', '<', $oneMonthAgo)->count();

    if ($totalOneMonthAgo === 0) {
      // If there were no sucursales a month ago, and now there are, it's 100% growth (or 0% if still none)
      return $currentTotal > 0 ? 100.0 : 0.0;
    }

    return round((($currentTotal - $totalOneMonthAgo) / $totalOneMonthAgo) * 100, 1);
  }

  /**
   * Get pharmacies by chain
   *
   * @param string $cadenaId
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public function getByChain(string $cadenaId)
  {
    return Sucursal::where('cadena_id', $cadenaId)
      ->with('cadenaFarmaceutica')
      ->get();
  }

  /**
   * Get pharmacy count by chain
   *
   * @return array
   */
  public function getCountByChain(): array
  {
    return Sucursal::select('cadena_id', DB::raw('COUNT(*) as count'))
      ->groupBy('cadena_id')
      ->get()
      ->toArray();
  }

  /**
   * Find a pharmacy by composite key
   *
   * @param string $cadenaId
   * @param string $sucursalId
   * @return Sucursal
   */
  public function findSucursal(string $cadenaId, string $sucursalId)
  {
    return Sucursal::where('cadena_id', $cadenaId)
      ->where('sucursal_id', $sucursalId)
      ->firstOrFail();
  }

  /**
   * Get all pharmacies with their schedules and chain info
   *
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public function getAllSucursalesWithRelations()
  {
    return Sucursal::with(['horarios', 'cadena'])->get();
  }
}
