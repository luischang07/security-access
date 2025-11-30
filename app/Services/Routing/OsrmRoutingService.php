<?php

namespace App\Services\Routing;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OsrmRoutingService implements RoutingServiceInterface
{
  protected string $baseUrl;

  public function __construct()
  {
    $this->baseUrl = config('services.osrm.url', 'http://router.project-osrm.org/route/v1/driving');
  }

  public function getRouteDetails(float $originLat, float $originLng, float $destLat, float $destLng): ?array
  {
    try {
      // OSRM expects {longitude},{latitude}
      $coordinates = "{$originLng},{$originLat};{$destLng},{$destLat}";
      // Request full overview to get the geometry
      $url = "{$this->baseUrl}/{$coordinates}?overview=full";

      $response = Http::get($url);

      if ($response->successful()) {
        $data = $response->json();
        if (isset($data['routes'][0])) {
          return [
            'duration' => (int) $data['routes'][0]['duration'],
            'geometry' => $data['routes'][0]['geometry'] // Encoded polyline
          ];
        }
      }
    } catch (\Exception $e) {
      Log::error("OSRM Routing Error: " . $e->getMessage());
    }

    return null;
  }
}
