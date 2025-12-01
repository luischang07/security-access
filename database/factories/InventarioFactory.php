<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class InventarioFactory extends Factory
{
  public function definition(): array
  {
    $minimo = $this->faker->numberBetween(5, 20);
    return [
      'stock_disponible' => $this->faker->numberBetween(0, 500),
      'minimo' => $minimo,
      'maximo' => $this->faker->numberBetween($minimo + 1, 100),
      'precio_unitario' => $this->faker->numberBetween(10, 1000),
    ];
  }
}
