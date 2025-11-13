<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DetalleLineaPedidoFactory extends Factory
{
  public function definition(): array
  {
    return [
      'cantidad_asignada' => $this->faker->numberBetween(1, 5),
      'cantidad_recolectada' => $this->faker->numberBetween(0, 5),
    ];
  }
}
