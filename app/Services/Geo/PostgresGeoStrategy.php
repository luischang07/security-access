<?php

namespace App\Services\Geo;

class PostgresGeoStrategy implements GeoSpatialStrategy
{
  public function getDistanceSql(): string
  {
    return "
            SELECT ST_DistanceSphere(
                ST_MakePoint(?, ?),
                ST_MakePoint(?, ?)
            ) as distance
        ";
  }

  public function getDistanceColumnSql(): string
  {
    return "ST_DistanceSphere(location::geometry, ST_MakePoint(?, ?))";
  }
}
