<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RutaRecoleccionFactory extends Factory
{
  public function definition(): array
  {
    return [
      'orden_visita' => $this->faker->numberBetween(1, 10),
      'estado_recoleccion' => $this->faker->randomElement(['pendiente', 'en_camino', 'completado', 'fallido']),
      'fecha_hora_visita' => $this->faker->dateTimeThisMonth(),
    ];
  }
}
