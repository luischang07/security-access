<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SucursalFactory extends Factory
{
  public function definition(): array
  {
    return [
      'nombre' => $this->faker->streetAddress(),
      'direccion' => $this->faker->address(),
      'latitud' => $this->faker->latitude(),
      'longitud' => $this->faker->longitude(),
    ];
  }
}
