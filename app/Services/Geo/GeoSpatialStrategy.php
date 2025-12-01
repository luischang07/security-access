<?php

namespace App\Services\Geo;

interface GeoSpatialStrategy
{
  /**
   * Get the SQL for calculating distance between two points.
   *
   * @return string
   */
  public function getDistanceSql(): string;

  /**
   * Get the SQL for calculating distance between a column and a point.
   *
   * @return string
   */
  public function getDistanceColumnSql(): string;
}
