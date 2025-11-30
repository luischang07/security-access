<?php

namespace App\Services\Geo;

use InvalidArgumentException;

class GeoStrategyFactory
{
  public static function make(string $driver): GeoSpatialStrategy
  {
    return match ($driver) {
      'pgsql' => new PostgresGeoStrategy(),
      'mysql', 'mariadb' => new MySqlGeoStrategy(),
      default => throw new InvalidArgumentException("Unsupported database driver: {$driver}"),
    };
  }
}
