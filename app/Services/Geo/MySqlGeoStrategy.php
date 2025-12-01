<?php

namespace App\Services\Geo;

class MySqlGeoStrategy implements GeoSpatialStrategy
{
  public function getDistanceSql(): string
  {
    return "
            SELECT ST_Distance_Sphere(
                Point(?, ?),
                Point(?, ?)
            ) as distance
        ";
  }

  public function getDistanceColumnSql(): string
  {
    return "ST_Distance_Sphere(location, Point(?, ?))";
  }
}
