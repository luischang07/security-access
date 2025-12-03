<?php

namespace App\Services\Routing;

interface RoutingServiceInterface
{
  /**
   * Get only the travel duration between two points (faster, no geometry).
   *
   * @param float $originLat
   * @param float $originLng
   * @param float $destLat
   * @param float $destLng
   * @return float|null Duration in seconds, or null if failed.
   */
  public function getTravelDuration(float $originLat, float $originLng, float $destLat, float $destLng): ?float;

  /**
   * Get the optimal trip (TSP) for a set of coordinates.
   *
   * @param array $coordinates Array of ['lat' => float, 'lng' => float]
   * @return array|null Array with 'duration', 'geometry', and 'waypoints_order'
   */
  public function getOptimalTrip(array $coordinates): ?array;
}
