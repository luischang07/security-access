<?php

namespace App\Services;

use App\Domain\Sucursal;
use Illuminate\Support\Facades\DB;

class GeoLocationService
{
  protected $routingService;

  public function __construct(\App\Services\Routing\RoutingServiceInterface $routingService = null)
  {
    // Optional injection to avoid breaking existing tests/instantiations immediately
    // In a real app, we'd bind this in a ServiceProvider
    $this->routingService = $routingService ?? new \App\Services\Routing\OsrmRoutingService();
  }

  /**
   * Calculate the distance between two points in meters using Spatial functions.
   * Supports both PostGIS (PostgreSQL) and MySQL via Strategy Pattern.
   * 
   * @param float $lat1
   * @param float $lng1
   * @param float $lat2
   * @param float $lng2
   * @return float Distance in meters
   */
  public function calculateDistance($lat1, $lng1, $lat2, $lng2)
  {
    $driver = DB::connection()->getDriverName();
    $strategy = \App\Services\Geo\GeoStrategyFactory::make($driver);

    $sql = $strategy->getDistanceSql();

    $result = DB::selectOne($sql, [$lng1, $lat1, $lng2, $lat2]);

    return $result->distance;
  }

  /**
   * Find the nearest branches that have stock for the given medications.
   * 
   * @param array $medicamentoIds List of medication IDs to check stock for.
   * @param float $userLat User's latitude (or primary branch latitude).
   * @param float $userLng User's longitude (or primary branch longitude).
   * @param int $excludeBranchId ID of the branch to exclude (the primary branch).
   * @param int $limit Max number of branches to return.
   * @return \Illuminate\Support\Collection
   */
  public function findNearestBranchesWithStock($medicamentoIds, $userLat, $userLng, $excludeBranchId, $limit = 5)
  {
    $driver = DB::connection()->getDriverName();
    $strategy = \App\Services\Geo\GeoStrategyFactory::make($driver);

    $distanceSql = $strategy->getDistanceColumnSql();

    $branches = DB::table('sucursales')
      ->join('inventarios', function ($join) {
        $join->on('sucursales.cadena_id', '=', 'inventarios.cadena_id')
          ->on('sucursales.sucursal_id', '=', 'inventarios.sucursal_id');
      })
      ->whereIn('inventarios.medicamento_id', $medicamentoIds)
      ->where('inventarios.stock_disponible', '>', 0)
      ->where(function ($query) use ($excludeBranchId) {
        if ($excludeBranchId) {
          $query->where('sucursales.sucursal_id', '!=', $excludeBranchId);
        }
      })
      ->select(
        'sucursales.*',
        DB::raw("$distanceSql as distance")
      )
      ->orderBy('distance', 'ASC')
      ->distinct() // MySQL distinct
      ->limit($limit)
      ->setBindings([$userLng, $userLat], 'select')
      ->get();

    return $branches;
  }

  /**
   * Find nearest branches using the Hybrid Algorithm (Prioritize Travel Time).
   * 
   * 1. Filter candidates by spatial distance (e.g. 20km radius) to limit API calls.
   * 2. Calculate actual travel time using OSRM.
   * 3. Sort by travel time.
   */
  public function findNearestBranchesHybrid($medicamentoIds, $userLat, $userLng, $excludeBranchId)
  {
    $candidates = $this->findNearestBranchesWithStock($medicamentoIds, $userLat, $userLng, $excludeBranchId, 10);

    $candidates->transform(function ($branch) use ($userLat, $userLng) {
      $details = $this->routingService->getRouteDetails($userLat, $userLng, $branch->latitud, $branch->longitud);

      // If OSRM fails or returns null, fallback to spatial distance (assuming 1m/s for sorting)
      // or just keep it at the end of the list.
      if ($details) {
        $branch->travel_time = $details['duration'];
        $branch->route_geometry = $details['geometry'];
      } else {
        $branch->travel_time = 999999;
        $branch->route_geometry = null;
      }

      return $branch;
    });

    $sorted = $candidates->sortBy(function ($branch) {
      return $branch->travel_time;
    });

    return $sorted->values();
  }
}
