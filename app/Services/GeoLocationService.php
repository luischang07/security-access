<?php

namespace App\Services;

use App\Domain\Sucursal;
use App\Services\Routing\RoutingServiceInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class GeoLocationService
{
  protected RoutingServiceInterface $routingService;

  public function __construct(?RoutingServiceInterface $routingService = null)
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
  public function calculateDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
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
   * @param string|null $excludeCadenaId Cadena ID of the branch to exclude (the primary branch).
   * @param string|null $excludeSucursalId Sucursal ID of the branch to exclude (the primary branch).
   * @param float $maxRadiusKm Maximum search radius in kilometers (default: 50km).
   * @return Collection<Sucursal>
   */
  public function findNearestBranchesWithStock(array $medicamentoIds, float $userLat, float $userLng, ?string $excludeCadenaId = null, ?string $excludeSucursalId = null, float $maxRadiusKm): Collection
  {
    $driver = DB::connection()->getDriverName();
    $strategy = \App\Services\Geo\GeoStrategyFactory::make($driver);

    $distanceSql = $strategy->getDistanceColumnSql();
    $maxDistanceMeters = $maxRadiusKm * 1000; // Convert km to meters

    $branches = DB::table('sucursales')
      ->join('inventarios', function ($join) {
        $join->on('sucursales.cadena_id', '=', 'inventarios.cadena_id')
          ->on('sucursales.sucursal_id', '=', 'inventarios.sucursal_id');
      })
      ->whereIn('inventarios.medicamento_id', $medicamentoIds)
      ->where('inventarios.stock_disponible', '>', 0)
      ->where(function ($query) use ($excludeCadenaId, $excludeSucursalId) {
        if ($excludeCadenaId && $excludeSucursalId) {
          $query->whereNot(function ($q) use ($excludeCadenaId, $excludeSucursalId) {
            $q->where('sucursales.cadena_id', '=', $excludeCadenaId)
              ->where('sucursales.sucursal_id', '=', $excludeSucursalId);
          });
        }
      })
      ->select(
        'sucursales.*',
        DB::raw("$distanceSql as distancia")
      )
      ->having('distancia', '<=', $maxDistanceMeters) // Filter by radius
      ->orderBy('distancia', 'ASC')
      ->distinct() // MySQL distinct
      ->setBindings([$userLng, $userLat], 'select')
      ->get();

    return $branches->map(function ($branch) {
      return Sucursal::crear($branch);
    });
  }

  /**
   * Find nearest branches using the Hybrid Algorithm (Prioritize Travel Time).
   * 
   * 1. Filter candidates by spatial distance radius (e.g. 50km) to limit API calls.
   * 2. Calculate actual travel time using OSRM.
   * 3. Sort by travel time.
   * 
   * @param array $medicamentoIds List of medication IDs to check stock for.
   * @param float $userLat User's latitude (or primary branch latitude).
   * @param float $userLng User's longitude (or primary branch longitude).
   * @param string|null $excludeCadenaId Cadena ID of the branch to exclude (the primary branch).
   * @param string|null $excludeSucursalId Sucursal ID of the branch to exclude (the primary branch).
   * @param float $maxRadiusKm Maximum search radius in kilometers (default: 50km).
   * @return Collection<array{sucursal: Sucursal, travel_time: float, route_geometry: ?string}>
   */
  public function findNearestBranchesHybrid(array $medicamentoIds, float $userLat, float $userLng, ?string $excludeCadenaId = null, ?string $excludeSucursalId = null, float $maxRadiusKm = 10.0): Collection
  {
    $candidates = $this->findNearestBranchesWithStock($medicamentoIds, $userLat, $userLng, $excludeCadenaId, $excludeSucursalId, $maxRadiusKm);

    $enrichedCandidates = $candidates->map(function (Sucursal $sucursal) use ($userLat, $userLng) {
      $details = $this->routingService->getRouteDetails($userLat, $userLng, $sucursal->getLatitud(), $sucursal->getLongitud());

      // If OSRM fails or returns null, fallback to spatial distance (assuming 1m/s for sorting)
      // or just keep it at the end of the list.
      if ($details) {
        $travelTime = $details['duration'];
        $routeGeometry = $details['geometry'];
      } else {
        $travelTime = 999999.0;
        $routeGeometry = null;
      }

      return [
        'sucursal' => $sucursal,
        'travel_time' => $travelTime,
        'route_geometry' => $routeGeometry,
      ];
    });

    $sorted = $enrichedCandidates->sortBy(function ($item) {
      return $item['travel_time'];
    });

    return $sorted->values();
  }

  public function getRoutingService(): RoutingServiceInterface
  {
    return $this->routingService;
  }
}
