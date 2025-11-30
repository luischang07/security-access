<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\GeoLocationService;
use App\Services\Geo\GeoStrategyFactory;
use App\Services\Geo\PostgresGeoStrategy;
use App\Services\Geo\MySqlGeoStrategy;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class GeoLocationServiceTest extends TestCase
{
  public function test_factory_returns_postgres_strategy()
  {
    $strategy = GeoStrategyFactory::make('pgsql');
    $this->assertInstanceOf(PostgresGeoStrategy::class, $strategy);
  }

  public function test_factory_returns_mysql_strategy()
  {
    $strategy = GeoStrategyFactory::make('mysql');
    $this->assertInstanceOf(MySqlGeoStrategy::class, $strategy);

    $strategy = GeoStrategyFactory::make('mariadb');
    $this->assertInstanceOf(MySqlGeoStrategy::class, $strategy);
  }





  public function test_factory_throws_exception_for_unsupported_driver()
  {
    $this->expectException(InvalidArgumentException::class);
    GeoStrategyFactory::make('sqlite');
  }

  public function test_calculate_distance_uses_strategy()
  {
    // Mock DB connection to return 'pgsql'
    DB::shouldReceive('connection->getDriverName')->andReturn('pgsql');

    // Mock DB selectOne
    $mockResult = (object) ['distance' => 100.5];
    DB::shouldReceive('selectOne')->once()->andReturn($mockResult);

    $service = new GeoLocationService();
    $distance = $service->calculateDistance(10, 20, 30, 40);

    $this->assertEquals(100.5, $distance);
  }

  public function test_find_nearest_branches_hybrid_sorts_by_time()
  {
    // Mock DB connection
    DB::shouldReceive('connection->getDriverName')->andReturn('pgsql');

    // Mock RoutingService
    $mockRouting = $this->mock(\App\Services\Routing\RoutingServiceInterface::class);
    $mockRouting->shouldReceive('getTravelTime')->andReturn(
      500, // Branch 1 (closer time)
      1000 // Branch 2 (further time)
    );

    // Mock DB query builder chain... this is complex to mock fully with Eloquent/QueryBuilder.
    // For a unit test, it's often easier to test the logic that sorts the collection
    // rather than the full DB query.
    // However, since we are testing the service method which does the query, 
    // we might need an integration test or a partial mock.

    // Let's partial mock the service to avoid the DB call for this specific test
    // and just test the sorting logic on a collection.

    $service = \Mockery::mock(GeoLocationService::class, [$mockRouting])->makePartial();

    $branch1 = (object) ['id' => 1, 'latitud' => 10, 'longitud' => 10, 'distance' => 100];
    $branch2 = (object) ['id' => 2, 'latitud' => 20, 'longitud' => 20, 'distance' => 50]; // Physically closer but slower

    $collection = collect([$branch1, $branch2]);

    $service->shouldReceive('findNearestBranchesWithStock')
      ->once()
      ->andReturn($collection);

    $sorted = $service->findNearestBranchesHybrid([], 0, 0, 0);

    $this->assertEquals(1, $sorted->first()->id); // Branch 1 should be first (500s vs 1000s)
    $this->assertEquals(500, $sorted->first()->travel_time);
  }
}
