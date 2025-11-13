<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'nombre' => fake()->name(),
      'correo' => fake()->unique()->safeEmail(),
      'direccion' => fake()->address(),
      'password' => Hash::make('password'),
      'session_token' => null,
      'ultimo_login' => null,
      'ultimo_cierre_sesion' => null,
    ];
  }

  public function demo(): static
  {
    return $this->state(fn(array $attributes) => [
      'nombre' => 'Demo User',
      'correo' => 'demo@example.com',
      'direccion' => '123 Demo Street',
      'password' => Hash::make('password'),
      'session_token' => null,
      'ultimo_login' => null,
      'ultimo_cierre_sesion' => null,
    ]);
  }
}
