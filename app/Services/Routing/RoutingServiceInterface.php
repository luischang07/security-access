<?php

namespace App\Services\Routing;

interface RoutingServiceInterface
{
  /**
   * Get the travel details between two points.
   *
   * @param float $originLat
   * @param float $originLng
   * @param float $destLat
   * @param float $destLng
   * @return array|null Array with 'duration' (seconds) and 'geometry' (string), or null.
   */
  public function getRouteDetails(float $originLat, float $originLng, float $destLat, float $destLng): ?array;
}
