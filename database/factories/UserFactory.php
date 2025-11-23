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
      'nombre' => fake()->firstName(),
      'apellido' => fake()->lastName(),
      'correo' => fake()->unique()->safeEmail(),
      'password' => Hash::make('password'),
      'session_token' => null,
      'ultimo_login' => null,
      'ultimo_cierre_sesion' => null,
    ];
  }

  public function demo(): static
  {
    return $this->state(fn(array $attributes) => [
      'nombre' => 'Demo',
      'apellido' => 'Demo',
      'correo' => 'demo@example.com',
      'password' => Hash::make('password'),
      'session_token' => null,
      'ultimo_login' => null,
      'ultimo_cierre_sesion' => null,
    ]);
  }
}
