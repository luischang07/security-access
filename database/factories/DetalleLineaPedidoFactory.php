<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DetalleLineaPedidoFactory extends Factory
{
  public function definition(): array
  {
    return [
      'cantidad_surtida' => $this->faker->numberBetween(0, 5),
      'precio_unitario' => $this->faker->randomFloat(2, 10, 100),
    ];
  }
}
