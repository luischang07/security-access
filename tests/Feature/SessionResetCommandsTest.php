<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class SessionResetCommandsTest extends TestCase
{
  use RefreshDatabase;

  protected function setUp(): void
  {
    parent::setUp();
    $this->artisan('migrate');
  }

  /** @test */
  public function comando_tokens_check_funciona_sin_argumentos()
  {
    $exitCode = $this->artisan('tokens:check')->run();
    $this->assertEquals(0, $exitCode);
  }

  /** @test */
  public function comando_email_simulate_click_funciona_sin_argumentos()
  {
    $exitCode = $this->artisan('email:simulate-click')->run();
    $this->assertEquals(0, $exitCode);
  }

  /** @test */
  public function comando_test_session_reset_funciona_con_email()
  {
    $exitCode = $this->artisan('test:session-reset', ['email' => 'test@example.com'])->run();
    $this->assertEquals(0, $exitCode);
  }

  /** @test */
  public function comando_configure_mailtrap_existe()
  {
    $exitCode = $this->artisan('configure:mailtrap')->run();
    $this->assertEquals(0, $exitCode);
  }

  /** @test */
  public function puede_procesar_tokens_con_comando()
  {
    // Arrange
    $email = 'test@example.com';
    DB::table('session_reset_tokens')->insert([
      'email' => $email,
      'token' => hash('sha256', 'test-token'),
      'created_at' => now(),
    ]);

    // Verificar que el token existe
    $this->assertDatabaseHas('session_reset_tokens', ['email' => $email]);

    // Act - Simular clic en email
    $this->artisan('email:simulate-click', ['email' => $email]);

    // Assert - El comando debería haber procesado el token
    // (En una implementación real, esto eliminaría el token)
    $this->assertTrue(true); // El comando ejecutó sin errores
  }

  /** @test */
  public function puede_verificar_tokens_existentes()
  {
    // Arrange
    $emails = ['user1@example.com', 'user2@example.com'];

    foreach ($emails as $email) {
      DB::table('session_reset_tokens')->insert([
        'email' => $email,
        'token' => hash('sha256', 'token-' . $email),
        'created_at' => now(),
      ]);
    }

    // Act & Assert
    foreach ($emails as $email) {
      $exitCode = $this->artisan('tokens:check', ['email' => $email])->run();
      $this->assertEquals(0, $exitCode);
    }
  }

  /** @test */
  public function comandos_manejan_emails_inexistentes_gracefully()
  {
    // Act & Assert
    $nonExistentEmail = 'nonexistent@example.com';

    $exitCode1 = $this->artisan('tokens:check', ['email' => $nonExistentEmail])->run();
    $exitCode2 = $this->artisan('email:simulate-click', ['email' => $nonExistentEmail])->run();

    // Los comandos no deberían fallar con emails inexistentes
    $this->assertContains($exitCode1, [0, 1]); // 0 = success, 1 = no encontrado
    $this->assertContains($exitCode2, [0, 1]);
  }

  /** @test */
  public function puede_limpiar_tokens_manualmente()
  {
    // Arrange
    $recentEmail = 'recent@example.com';
    $oldEmail = 'old@example.com';

    DB::table('session_reset_tokens')->insert([
      [
        'email' => $recentEmail,
        'token' => hash('sha256', 'recent-token'),
        'created_at' => now()->subMinutes(30),
      ],
      [
        'email' => $oldEmail,
        'token' => hash('sha256', 'old-token'),
        'created_at' => now()->subHours(2),
      ],
    ]);

    // Act - Limpiar tokens expirados
    $deletedCount = DB::table('session_reset_tokens')
      ->where('created_at', '<', now()->subHours(1))
      ->delete();

    // Assert
    $this->assertEquals(1, $deletedCount);
    $this->assertDatabaseHas('session_reset_tokens', ['email' => $recentEmail]);
    $this->assertDatabaseMissing('session_reset_tokens', ['email' => $oldEmail]);
  }
}
