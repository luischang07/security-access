<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PacienteFactory extends Factory
{
  public function definition(): array
  {
    return [
      'monto_penalizacion' => $this->faker->randomFloat(2, 0, 500),
    ];
  }
}
