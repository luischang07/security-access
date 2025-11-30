<?php

namespace App\Services\Routing;

interface RoutingServiceInterface
{
  /**
   * Get the travel time between two points in seconds.
   *
   * @param float $originLat
   * @param float $originLng
   * @param float $destLat
   * @param float $destLng
   * @return int|null Duration in seconds, or null if route not found.
   */
  public function getTravelTime(float $originLat, float $originLng, float $destLat, float $destLng): ?int;
}
