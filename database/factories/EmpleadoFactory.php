<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EmpleadoFactory extends Factory
{
  public function definition(): array
  {
    return [
      'fecha_ingreso' => $this->faker->dateTimeBetween('-5 years', 'now'),
    ];
  }
}
