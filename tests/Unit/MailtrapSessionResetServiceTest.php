<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MailtrapSessionResetServiceTest extends TestCase
{
  use RefreshDatabase;

  protected function setUp(): void
  {
    parent::setUp();

    // Ejecutar migraciones
    $this->artisan('migrate');
  }

  /** @test */
  public function puede_crear_token_reset_en_base_datos()
  {
    // Arrange
    $email = 'test@example.com';
    $token = 'test-token-123';
    $hashedToken = hash('sha256', $token);

    // Act
    DB::table('session_reset_tokens')->insert([
      'email' => $email,
      'token' => $hashedToken,
      'created_at' => now(),
    ]);

    // Assert
    $this->assertDatabaseHas('session_reset_tokens', [
      'email' => $email,
      'token' => $hashedToken
    ]);
  }

  /** @test */
  public function puede_eliminar_tokens_expirados()
  {
    // Arrange
    $validEmail = 'valid@example.com';
    $expiredEmail = 'expired@example.com';

    // Token válido (30 minutos)
    DB::table('session_reset_tokens')->insert([
      'email' => $validEmail,
      'token' => hash('sha256', 'valid-token'),
      'created_at' => now()->subMinutes(30),
    ]);

    // Token expirado (2 horas)
    DB::table('session_reset_tokens')->insert([
      'email' => $expiredEmail,
      'token' => hash('sha256', 'expired-token'),
      'created_at' => now()->subHours(2),
    ]);

    // Act
    $deletedCount = DB::table('session_reset_tokens')
      ->where('created_at', '<', now()->subHours(1))
      ->delete();

    // Assert
    $this->assertEquals(1, $deletedCount);

    $this->assertDatabaseHas('session_reset_tokens', [
      'email' => $validEmail
    ]);

    $this->assertDatabaseMissing('session_reset_tokens', [
      'email' => $expiredEmail
    ]);
  }

  /** @test */
  public function puede_verificar_token_valido()
  {
    // Arrange
    $email = 'test@example.com';
    $token = 'valid-token-123';
    $hashedToken = hash('sha256', $token);

    DB::table('session_reset_tokens')->insert([
      'email' => $email,
      'token' => $hashedToken,
      'created_at' => now(),
    ]);

    // Act
    $resetToken = DB::table('session_reset_tokens')
      ->where('token', $hashedToken)
      ->where('created_at', '>', now()->subHours(1))
      ->first();

    // Assert
    $this->assertNotNull($resetToken);
    $this->assertEquals($email, $resetToken->email);
  }

  /** @test */
  public function rechaza_token_expirado()
  {
    // Arrange
    $email = 'test@example.com';
    $token = 'expired-token';
    $hashedToken = hash('sha256', $token);

    // Insertar token expirado
    DB::table('session_reset_tokens')->insert([
      'email' => $email,
      'token' => $hashedToken,
      'created_at' => now()->subHours(2), // Expirado
    ]);

    // Act
    $resetToken = DB::table('session_reset_tokens')
      ->where('token', $hashedToken)
      ->where('created_at', '>', now()->subHours(1))
      ->first();

    // Assert
    $this->assertNull($resetToken);
  }

  /** @test */
  public function puede_contar_tokens_pendientes()
  {
    // Arrange
    DB::table('session_reset_tokens')->insert([
      ['email' => 'user1@example.com', 'token' => hash('sha256', 'token1'), 'created_at' => now()],
      ['email' => 'user2@example.com', 'token' => hash('sha256', 'token2'), 'created_at' => now()],
      ['email' => 'user3@example.com', 'token' => hash('sha256', 'token3'), 'created_at' => now()],
    ]);

    // Act
    $count = DB::table('session_reset_tokens')->count();

    // Assert
    $this->assertEquals(3, $count);
  }

  /** @test */
  public function puede_actualizar_o_insertar_token()
  {
    // Arrange
    $email = 'test@example.com';
    $firstToken = hash('sha256', 'first-token');
    $secondToken = hash('sha256', 'second-token');

    // Act - Primera inserción
    DB::table('session_reset_tokens')->updateOrInsert(
      ['email' => $email],
      ['token' => $firstToken, 'created_at' => now()]
    );

    $this->assertDatabaseHas('session_reset_tokens', [
      'email' => $email,
      'token' => $firstToken
    ]);

    // Act - Actualización
    DB::table('session_reset_tokens')->updateOrInsert(
      ['email' => $email],
      ['token' => $secondToken, 'created_at' => now()]
    );

    // Assert
    $this->assertDatabaseHas('session_reset_tokens', [
      'email' => $email,
      'token' => $secondToken
    ]);

    $this->assertDatabaseMissing('session_reset_tokens', [
      'email' => $email,
      'token' => $firstToken
    ]);

    // Verificar que solo hay un registro para este email
    $count = DB::table('session_reset_tokens')->where('email', $email)->count();
    $this->assertEquals(1, $count);
  }
}
