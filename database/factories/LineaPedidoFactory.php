<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LineaPedidoFactory extends Factory
{
  public function definition(): array
  {
    return [
      'cantidad_solicitada' => $this->faker->numberBetween(1, 10),
      'precio_unitario' => $this->faker->randomFloat(2, 5, 100),
    ];
  }
}
