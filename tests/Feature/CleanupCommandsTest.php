<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class CleanupCommandsTest extends TestCase
{
  use RefreshDatabase;

  protected function setUp(): void
  {
    parent::setUp();
    $this->artisan('migrate');
  }

  /** @test */
  public function comando_check_tokens_muestra_tokens_existentes()
  {
    // Arrange
    $email = 'twice@jypetwiceinfo.com';
    DB::table('session_reset_tokens')->insert([
      'email' => $email,
      'token' => hash('sha256', 'test-token'),
      'created_at' => now(),
    ]);

    // Act
    $this->artisan('tokens:check', ['email' => $email])
      ->expectsOutput('🔍 Buscando tokens para: ' . $email)
      ->assertExitCode(0);
  }

  /** @test */
  public function comando_check_tokens_indica_cuando_no_hay_tokens()
  {
    // Arrange
    $email = 'nonexistent@example.com';

    // Act
    $this->artisan('tokens:check', ['email' => $email])
      ->expectsOutput('🔍 Buscando tokens para: ' . $email)
      ->expectsOutput('❌ No se encontraron tokens')
      ->assertExitCode(0);
  }

  /** @test */
  public function comando_simulate_email_click_procesa_token_valido()
  {
    // Arrange
    $email = 'twice@jypetwiceinfo.com';
    $token = 'valid-token-123';
    $hashedToken = hash('sha256', $token);

    DB::table('session_reset_tokens')->insert([
      'email' => $email,
      'token' => $hashedToken,
      'created_at' => now(),
    ]);

    // Act
    $this->artisan('email:simulate-click', ['email' => $email])
      ->expectsOutput('🔍 Buscando token más reciente para: ' . $email)
      ->assertExitCode(0);

    // Assert
    $this->assertDatabaseMissing('session_reset_tokens', [
      'email' => $email,
    ]);
  }

  /** @test */
  public function comando_simulate_email_click_maneja_email_sin_token()
  {
    // Arrange
    $email = 'notoken@example.com';

    // Act
    $this->artisan('email:simulate-click', ['email' => $email])
      ->expectsOutput('🔍 Buscando token más reciente para: ' . $email)
      ->expectsOutput('❌ No se encontraron tokens para este email')
      ->assertExitCode(0);
  }

  /** @test */
  public function puede_limpiar_tokens_expirados_manualmente()
  {
    // Arrange
    $validEmail = 'valid@example.com';
    $expiredEmail = 'expired@example.com';

    // Token válido
    DB::table('session_reset_tokens')->insert([
      'email' => $validEmail,
      'token' => hash('sha256', 'valid-token'),
      'created_at' => now()->subMinutes(30),
    ]);

    // Token expirado
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
      'email' => $validEmail,
    ]);

    $this->assertDatabaseMissing('session_reset_tokens', [
      'email' => $expiredEmail,
    ]);
  }

  /** @test */
  public function puede_verificar_estructura_de_tabla_tokens()
  {
    // Act & Assert
    $this->assertTrue(
      DB::getSchemaBuilder()->hasTable('session_reset_tokens'),
      'La tabla session_reset_tokens debe existir'
    );

    $this->assertTrue(
      DB::getSchemaBuilder()->hasColumn('session_reset_tokens', 'email'),
      'La columna email debe existir'
    );

    $this->assertTrue(
      DB::getSchemaBuilder()->hasColumn('session_reset_tokens', 'token'),
      'La columna token debe existir'
    );

    $this->assertTrue(
      DB::getSchemaBuilder()->hasColumn('session_reset_tokens', 'created_at'),
      'La columna created_at debe existir'
    );
  }

  /** @test */
  public function comando_test_session_reset_requiere_email()
  {
    // Act & Assert
    $this->artisan('test:session-reset')
      ->expectsOutput('❌ Por favor, proporciona un email como argumento')
      ->assertExitCode(1);
  }

  /** @test */
  public function puede_contar_tokens_por_usuario()
  {
    // Arrange
    $user1Email = 'user1@example.com';
    $user2Email = 'user2@example.com';

    DB::table('session_reset_tokens')->insert([
      ['email' => $user1Email, 'token' => hash('sha256', 'token1'), 'created_at' => now()],
      ['email' => $user1Email, 'token' => hash('sha256', 'token2'), 'created_at' => now()],
      ['email' => $user2Email, 'token' => hash('sha256', 'token3'), 'created_at' => now()],
    ]);

    // Act
    $user1Count = DB::table('session_reset_tokens')
      ->where('email', $user1Email)
      ->count();

    $user2Count = DB::table('session_reset_tokens')
      ->where('email', $user2Email)
      ->count();

    $totalCount = DB::table('session_reset_tokens')->count();

    // Assert
    $this->assertEquals(2, $user1Count);
    $this->assertEquals(1, $user2Count);
    $this->assertEquals(3, $totalCount);
  }

  /** @test */
  public function puede_obtener_token_mas_reciente()
  {
    // Arrange
    $email = 'test@example.com';
    $olderToken = hash('sha256', 'older-token');
    $newerToken = hash('sha256', 'newer-token');

    // Insertar tokens con diferentes timestamps
    DB::table('session_reset_tokens')->insert([
      [
        'email' => $email,
        'token' => $olderToken,
        'created_at' => now()->subMinutes(10),
      ],
      [
        'email' => $email,
        'token' => $newerToken,
        'created_at' => now()->subMinutes(5),
      ],
    ]);

    // Act
    $latestToken = DB::table('session_reset_tokens')
      ->where('email', $email)
      ->orderBy('created_at', 'desc')
      ->first();

    // Assert
    $this->assertNotNull($latestToken);
    $this->assertEquals($newerToken, $latestToken->token);
  }
}
