<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\Routing\OsrmRoutingService;
use Illuminate\Support\Facades\Http;

class OsrmRoutingServiceTest extends TestCase
{
  public function test_get_travel_time_returns_duration()
  {
    Http::fake([
      'router.project-osrm.org/*' => Http::response([
        'routes' => [
          ['duration' => 1234]
        ]
      ], 200)
    ]);

    $service = new OsrmRoutingService();
    $time = $service->getTravelTime(10, 10, 20, 20);

    $this->assertEquals(1234, $time);
  }

  public function test_get_travel_time_returns_null_on_failure()
  {
    Http::fake([
      'router.project-osrm.org/*' => Http::response([], 500)
    ]);

    $service = new OsrmRoutingService();
    $time = $service->getTravelTime(10, 10, 20, 20);

    $this->assertNull($time);
  }
}
