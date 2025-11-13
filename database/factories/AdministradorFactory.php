<?php

namespace Database\Factories;

use App\Models\Administrador;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdministradorFactory extends Factory
{
  protected $model = Administrador::class;

  public function definition(): array
  {
    return [
      'rol_admin' => $this->faker->randomElement(['super', 'moderador']),
      'ultimo_login' => $this->faker->dateTimeThisYear(),
    ];
  }
}
