<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PedidoFactory extends Factory
{
  public function definition(): array
  {
    $fechaPedido = $this->faker->dateTimeBetween('-3 months', 'now');
    $estado = $this->faker->randomElement(['confirmado', 'listo', 'completado', 'cancelado']);

    return [
      'fecha_pedido' => $fechaPedido,
      'estatus' => $estado,
      'costo_total' => $this->faker->randomFloat(2, 10, 500),
    ];
  }
}
