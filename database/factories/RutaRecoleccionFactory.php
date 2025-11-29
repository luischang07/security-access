<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RutaRecoleccionFactory extends Factory
{
  public function definition(): array
  {
    return [
      'orden_recoleccion' => $this->faker->numberBetween(1, 10),
    ];
  }
}
