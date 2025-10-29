<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class LoginThrottlingTest extends TestCase
{
  use RefreshDatabase;

  private const TEST_EMAIL = 'throttle@example.com';
  private const TEST_PASSWORD = 'password123';
  private const WRONG_PASSWORD = 'wrong-password';

  protected function setUp(): void
  {
    parent::setUp();
    $this->artisan('migrate');
  }

  /** @test */
  public function permite_login_exitoso_sin_intentos_previos()
  {
    // Arrange
    $user = User::factory()->create([
      'email' => self::TEST_EMAIL,
      'password' => bcrypt(self::TEST_PASSWORD),
    ]);

    // Act
    $response = $this->post('/login', [
      'email' => self::TEST_EMAIL,
      'password' => self::TEST_PASSWORD,
    ]);

    // Assert
    $response->assertRedirect('/dashboard');
    $this->assertAuthenticatedAs($user);
  }

  /** @test */
  public function registra_intento_fallido_en_base_datos()
  {
    // Arrange
    User::factory()->create([
      'email' => self::TEST_EMAIL,
      'password' => bcrypt(self::TEST_PASSWORD),
    ]);

    // Act
    $response = $this->post('/login', [
      'email' => self::TEST_EMAIL,
      'password' => self::WRONG_PASSWORD,
    ]);

    // Assert
    $response->assertSessionHasErrors(['email']);
    $this->assertGuest();

    // Verificar registro en BD
    $this->assertDatabaseHas('login_attempts', [
      'identifier' => self::TEST_EMAIL,
      'attempts' => 1,
    ]);
  }

  /** @test */
  public function incrementa_contador_con_multiples_fallos()
  {
    // Arrange
    User::factory()->create([
      'email' => self::TEST_EMAIL,
      'password' => bcrypt(self::TEST_PASSWORD),
    ]);

    // Act - Primer intento fallido
    $this->post('/login', [
      'email' => self::TEST_EMAIL,
      'password' => self::WRONG_PASSWORD,
    ]);

    // Act - Segundo intento fallido
    $this->post('/login', [
      'email' => self::TEST_EMAIL,
      'password' => self::WRONG_PASSWORD,
    ]);

    // Assert
    $this->assertDatabaseHas('login_attempts', [
      'identifier' => self::TEST_EMAIL,
      'attempts' => 2,
    ]);
  }

  /** @test */
  public function bloquea_despues_de_4_intentos_fallidos()
  {
    // Arrange
    $user = User::factory()->create([
      'email' => self::TEST_EMAIL,
      'password' => bcrypt(self::TEST_PASSWORD),
    ]);

    // Act - Realizar 4 intentos fallidos
    for ($i = 0; $i < 4; $i++) {
      $this->post('/login', [
        'email' => self::TEST_EMAIL,
        'password' => self::WRONG_PASSWORD,
      ]);
    }

    // Act - Quinto intento (debería estar bloqueado incluso con credenciales correctas)
    $response = $this->post('/login', [
      'email' => self::TEST_EMAIL,
      'password' => self::TEST_PASSWORD, // Credenciales correctas
    ]);

    // Assert
    $response->assertSessionHasErrors(['email']);
    $this->assertGuest();

    // Verificar que el mensaje incluye información sobre el bloqueo
    $errors = session('errors')->getBag('default');
    $this->assertStringContainsString('demasiados intentos', $errors->first('email'));
  }

  /** @test */
  public function reinicia_contador_despues_de_login_exitoso()
  {
    // Arrange
    $user = User::factory()->create([
      'email' => self::TEST_EMAIL,
      'password' => bcrypt(self::TEST_PASSWORD),
    ]);

    // Act - Realizar algunos intentos fallidos
    for ($i = 0; $i < 2; $i++) {
      $this->post('/login', [
        'email' => self::TEST_EMAIL,
        'password' => self::WRONG_PASSWORD,
      ]);
    }

    // Verificar que hay intentos registrados
    $this->assertDatabaseHas('login_attempts', [
      'identifier' => self::TEST_EMAIL,
      'attempts' => 2,
    ]);

    // Act - Login exitoso
    $response = $this->post('/login', [
      'email' => self::TEST_EMAIL,
      'password' => self::TEST_PASSWORD,
    ]);

    // Assert
    $response->assertRedirect('/dashboard');
    $this->assertAuthenticatedAs($user);

    // Verificar que se limpió el contador
    $this->assertDatabaseMissing('login_attempts', [
      'identifier' => self::TEST_EMAIL,
    ]);
  }

  /** @test */
  public function desbloquea_despues_del_tiempo_de_cooldown()
  {
    // Arrange
    $user = User::factory()->create([
      'email' => self::TEST_EMAIL,
      'password' => bcrypt(self::TEST_PASSWORD),
    ]);

    // Simular bloqueo insertando directamente en BD con timestamp anterior
    DB::table('login_attempts')->insert([
      'identifier' => self::TEST_EMAIL,
      'attempts' => 4,
      'last_attempt' => now()->subMinutes(2), // Hace 2 minutos (cooldown es 1 minuto)
      'created_at' => now(),
      'updated_at' => now(),
    ]);

    // Act - Intentar login después del cooldown
    $response = $this->post('/login', [
      'email' => self::TEST_EMAIL,
      'password' => self::TEST_PASSWORD,
    ]);

    // Assert
    $response->assertRedirect('/dashboard');
    $this->assertAuthenticatedAs($user);
  }

  /** @test */
  public function mantiene_bloqueo_durante_cooldown()
  {
    // Arrange
    User::factory()->create([
      'email' => self::TEST_EMAIL,
      'password' => bcrypt(self::TEST_PASSWORD),
    ]);

    // Simular bloqueo reciente
    DB::table('login_attempts')->insert([
      'identifier' => self::TEST_EMAIL,
      'attempts' => 4,
      'last_attempt' => now()->subSeconds(30), // Hace 30 segundos (cooldown es 1 minuto)
      'created_at' => now(),
      'updated_at' => now(),
    ]);

    // Act
    $response = $this->post('/login', [
      'email' => self::TEST_EMAIL,
      'password' => self::TEST_PASSWORD,
    ]);

    // Assert
    $response->assertSessionHasErrors(['email']);
    $this->assertGuest();
  }

  /** @test */
  public function diferentes_emails_tienen_contadores_independientes()
  {
    // Arrange
    $email1 = 'user1@example.com';
    $email2 = 'user2@example.com';

    User::factory()->create([
      'email' => $email1,
      'password' => bcrypt(self::TEST_PASSWORD),
    ]);

    User::factory()->create([
      'email' => $email2,
      'password' => bcrypt(self::TEST_PASSWORD),
    ]);

    // Act - Bloquear el primer usuario
    for ($i = 0; $i < 4; $i++) {
      $this->post('/login', [
        'email' => $email1,
        'password' => self::WRONG_PASSWORD,
      ]);
    }

    // Act - El segundo usuario debería poder hacer login normalmente
    $response = $this->post('/login', [
      'email' => $email2,
      'password' => self::TEST_PASSWORD,
    ]);

    // Assert
    $response->assertRedirect('/dashboard');

    // Verificar contadores independientes
    $this->assertDatabaseHas('login_attempts', [
      'identifier' => $email1,
      'attempts' => 4,
    ]);

    $this->assertDatabaseMissing('login_attempts', [
      'identifier' => $email2,
    ]);
  }

  /** @test */
  public function puede_limpiar_intentos_expirados()
  {
    // Arrange
    $recentEmail = 'recent@example.com';
    $oldEmail = 'old@example.com';

    // Intento reciente
    DB::table('login_attempts')->insert([
      'identifier' => $recentEmail,
      'attempts' => 2,
      'last_attempt' => now()->subMinutes(30),
      'created_at' => now(),
      'updated_at' => now(),
    ]);

    // Intento muy antiguo
    DB::table('login_attempts')->insert([
      'identifier' => $oldEmail,
      'attempts' => 3,
      'last_attempt' => now()->subDays(2),
      'created_at' => now(),
      'updated_at' => now(),
    ]);

    // Act - Limpiar intentos antiguos (más de 1 día)
    $deletedCount = DB::table('login_attempts')
      ->where('last_attempt', '<', now()->subDay())
      ->delete();

    // Assert
    $this->assertEquals(1, $deletedCount);

    $this->assertDatabaseHas('login_attempts', [
      'identifier' => $recentEmail,
    ]);

    $this->assertDatabaseMissing('login_attempts', [
      'identifier' => $oldEmail,
    ]);
  }
}
