<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class NotificacionFactory extends Factory
{
  public function definition(): array
  {
    return [
      'tipo' => $this->faker->randomElement(['pedido', 'penalizacion', 'sistema', 'entrega']),
      'mensaje' => $this->faker->sentence(12),
      'fecha_hora' => $this->faker->dateTimeThisMonth(),
      'leida' => $this->faker->boolean(30),
    ];
  }
}
