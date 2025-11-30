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

  public function getTravelTime(float $originLat, float $originLng, float $destLat, float $destLng): ?int
  {
    try {
      // OSRM expects {longitude},{latitude}
      $coordinates = "{$originLng},{$originLat};{$destLng},{$destLat}";
      $url = "{$this->baseUrl}/{$coordinates}";

      $response = Http::get($url);

      if ($response->successful()) {
        $data = $response->json();
        if (isset($data['routes'][0]['duration'])) {
          return (int) $data['routes'][0]['duration'];
        }
      }
    } catch (\Exception $e) {
      Log::error("OSRM Routing Error: " . $e->getMessage());
    }

    return null;
  }
}
