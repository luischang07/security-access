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
      $coordinates = "{$originLng},{$originLat};{$destLng},{$destLat}";
      $url = "{$this->baseUrl}/{$coordinates}?overview=full";

      $response = Http::get($url);

      if ($response->successful()) {
        $data = $response->json();
        if (isset($data['routes'][0])) {
          return [
            'duration' => (int) $data['routes'][0]['duration'],
            'geometry' => $data['routes'][0]['geometry']
          ];
        }
      }
    } catch (\Exception $e) {
      Log::error("OSRM Routing Error: " . $e->getMessage());
    }

    return null;
  }

  public function getTravelDuration(float $originLat, float $originLng, float $destLat, float $destLng): ?float
  {
    try {
      $coordinates = "{$originLng},{$originLat};{$destLng},{$destLat}";
      $url = "{$this->baseUrl}/{$coordinates}?overview=false";

      $response = Http::get($url);

      if ($response->successful()) {
        $data = $response->json();
        if (isset($data['routes'][0]['duration'])) {
          return (float) $data['routes'][0]['duration'];
        }
      }
    } catch (\Exception $e) {
      Log::error("OSRM Duration Error: " . $e->getMessage());
    }

    return null;
  }

  public function getOptimalTrip(array $coordinates): ?array
  {
    if (empty($coordinates)) {
      return null;
    }

    $coordsString = implode(';', array_map(function ($coord) {
      return "{$coord['lng']},{$coord['lat']}";
    }, $coordinates));

    $urlParts = parse_url($this->baseUrl);
    $host = $urlParts['scheme'] . '://' . $urlParts['host'] . (isset($urlParts['port']) ? ':' . $urlParts['port'] : '');

    $tripUrl = "{$host}/trip/v1/driving/{$coordsString}?overview=full&source=first&roundtrip=true";

    try {
      $response = Http::get($tripUrl);

      if ($response->successful()) {
        $data = $response->json();
        if (isset($data['trips'][0])) {
          return [
            'duration' => (int) $data['trips'][0]['duration'],
            'geometry' => $data['trips'][0]['geometry'],
            'waypoints' => $data['waypoints'] ?? []
          ];
        }
      }
    } catch (\Exception $e) {
      Log::error("OSRM Trip Error: " . $e->getMessage());
    }

    return null;
  }
}
