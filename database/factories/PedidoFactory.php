<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PedidoFactory extends Factory
{
  public function definition(): array
  {
    $fechaPedido = $this->faker->dateTimeBetween('-3 months', 'now');
    $estado = $this->faker->randomElement(['pendiente', 'en_proceso', 'completado', 'cancelado']);

    return [
      'fecha_pedido' => $fechaPedido,
      'fecha_entrega' => $estado === 'completado'
        ? $this->faker->dateTimeBetween($fechaPedido, 'now')
        : null,
      'estado' => $estado,
      'costo_total' => $this->faker->randomFloat(2, 10, 500),
    ];
  }
}
