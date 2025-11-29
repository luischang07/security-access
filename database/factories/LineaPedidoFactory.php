<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LineaPedidoFactory extends Factory
{
  public function definition(): array
  {
    return [
      'cantidad' => $this->faker->numberBetween(1, 10),
    ];
  }
}
