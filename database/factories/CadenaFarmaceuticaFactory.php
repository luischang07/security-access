<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CadenaFarmaceuticaFactory extends Factory
{
  public function definition(): array
  {
    return [
      'razon_social' => $this->faker->company(),
      'name' => $this->faker->companySuffix() . ' Pharmacy',
    ];
  }
}
