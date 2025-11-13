<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MedicamentoFactory extends Factory
{
  public function definition(): array
  {
    return [
      'nombre' => $this->faker->randomElement([
        'Paracetamol',
        'Ibuprofeno',
        'Amoxicilina',
        'Omeprazol',
        'Losartán',
        'Metformina',
        'Atorvastatina',
        'Aspirina',
        'Diclofenaco',
        'Cetirizina',
        'Loratadina',
        'Ranitidina'
      ]),
      'descripcion' => $this->faker->sentence(8),
      'unidad_medida' => $this->faker->randomElement(['mg', 'ml', 'g', 'unidades']),
      'unidades' => $this->faker->randomElement(['comprimidos', 'cápsulas', 'jarabe', 'inyectable', 'gotas']),
    ];
  }
}
