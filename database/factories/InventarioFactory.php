<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class InventarioFactory extends Factory
{
  public function definition(): array
  {
    return [
      'stock_disponible' => $this->faker->numberBetween(0, 500),
      'minimo' => $this->faker->numberBetween(5, 20),
      'maximo' => $this->faker->numberBetween(5, 20),
      'precio_unitario' => $this->faker->numberBetween(10, 1000),
    ];
  }
}
