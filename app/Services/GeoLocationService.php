<?php

namespace App\Services;

use App\Domain\Sucursal;
use App\Repositories\BaseDatos;
use App\Services\Routing\RoutingServiceInterface;
use Illuminate\Support\Collection;

class GeoLocationService
{
  protected RoutingServiceInterface $routingService;
  protected BaseDatos $baseDatos;

  public function __construct(RoutingServiceInterface $routingService, BaseDatos $baseDatos)
  {
    $this->routingService = $routingService;
    $this->baseDatos = $baseDatos;
  }

  /**
   * Calculate the distance between two points in meters.
   * Delegates to repository layer.
   *
   * @return float Distance in meters
   */
  public function calculateDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
  {
    return $this->baseDatos->calculateDistance($lat1, $lng1, $lat2, $lng2);
  }

  /**
   * Find nearest branches using the Hybrid Algorithm (Prioritize Travel Time).
   * 
   * 1. Filter candidates by spatial distance radius (e.g. 10km) to limit API calls.
   * 2. Calculate actual travel time using OSRM (duration only, no geometry).
   * 3. Sort by travel time.
   * 
   * @param array $medicamentoIds List of medication IDs to check stock for.
   * @param float $originLat Origin branch latitude.
   * @param float $originLng Origin branch longitude.
   * @param string|null $excludeCadenaId Cadena ID of the branch to exclude (the origin branch).
   * @param string|null $excludeSucursalId Sucursal ID of the branch to exclude (the origin branch).
   * @param float $maxRadiusKm Maximum search radius in kilometers (default: 10km).
   * @return Collection<Sucursal> Branches sorted by travel time (closest first)
   */
  public function buscarSucursalesPorTiempoDeViaje(
    array $medicamentoIds,
    float $originLat,
    float $originLng,
    ?string $excludeCadenaId = null,
    ?string $excludeSucursalId = null,
    float $maxRadiusKm = 10.0
  ): Collection {

    $sucCandidatas = $this->baseDatos->buscarSucursalesCercanasConStock(
      $medicamentoIds,
      $originLat,
      $originLng,
      $excludeCadenaId,
      $excludeSucursalId,
      $maxRadiusKm
    );

    return $this->ordenarPorTiempoDeViaje($sucCandidatas, $originLat, $originLng);
  }

  /**
   * Ordena sucursales por tiempo de viaje desde el origen.
   * 
   * @param Collection<Sucursal> $sucCandidatas
   * @param float $originLat
   * @param float $originLng
   * @return Collection<Sucursal> Sorted by travel duration (closest first)
   */
  private function ordenarPorTiempoDeViaje(Collection $sucCandidatas, float $originLat, float $originLng): Collection
  {
    $sucursalesConDuracion = $sucCandidatas->map(function (Sucursal $sucursal) use ($originLat, $originLng) {
      $duration = $this->routingService->getTravelDuration($originLat, $originLng, $sucursal->getLatitud(), $sucursal->getLongitud());

      if ($duration === null) {
        $duration = 999999.0;
      }

      return [
        'sucursal' => $sucursal,
        'duration' => $duration,
      ];
    });

    return $sucursalesConDuracion
      ->sortBy('duration')
      ->pluck('sucursal')
      ->values();
  }

  public function getRoutingService(): RoutingServiceInterface
  {
    return $this->routingService;
  }
}
