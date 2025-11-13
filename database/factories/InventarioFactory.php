<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class InventarioFactory extends Factory
{
  public function definition(): array
  {
    return [
      'cantidad' => $this->faker->numberBetween(0, 500),
    ];
  }
}
