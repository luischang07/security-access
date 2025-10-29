<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class AuthenticationFlowTest extends TestCase
{
  use RefreshDatabase;

  private const TEST_EMAIL = 'twice@jypetwiceinfo.com';
  private const TEST_PASSWORD = 'NipSeguro123!';
  private const TEST_NAME = 'Twice';

  protected function setUp(): void
  {
    parent::setUp();

    // Ejecutar migraciones
    $this->artisan('migrate');

    // Configurar Mailtrap para testing
    config(['services.mailtrap.token' => null]); // Usar SMTP fallback
    Mail::fake();
  }

  /** @test */
  public function usuario_puede_registrarse_exitosamente()
  {
    // Act
    $response = $this->post('/register', [
      'name' => self::TEST_NAME,
      'email' => self::TEST_EMAIL,
      'password' => self::TEST_PASSWORD,
      'password_confirmation' => self::TEST_PASSWORD,
    ]);

    // Assert
    $response->assertRedirect('/dashboard');
    $this->assertDatabaseHas('users', [
      'email' => self::TEST_EMAIL,
      'name' => self::TEST_NAME,
    ]);

    $user = User::where('email', self::TEST_EMAIL)->first();
    $this->assertNotNull($user->session_expires_at);
  }

  /** @test */
  public function usuario_puede_iniciar_sesion_sin_sesion_previa()
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

    // Verificar que se estableció la expiración de sesión
    $user->refresh();
    $this->assertNotNull($user->session_expires_at);
  }

  /** @test */
  public function usuario_no_puede_iniciar_sesion_con_credenciales_incorrectas()
  {
    // Arrange
    User::factory()->create([
      'email' => self::TEST_EMAIL,
      'password' => bcrypt(self::TEST_PASSWORD),
    ]);

    // Act
    $response = $this->post('/login', [
      'email' => self::TEST_EMAIL,
      'password' => 'wrong-password',
    ]);

    // Assert
    $response->assertSessionHasErrors(['email']);
    $this->assertGuest();
  }

  /** @test */
  public function usuario_con_sesion_activa_recibe_mensaje_de_sesion_existente()
  {
    // Arrange
    $user = User::factory()->create([
      'email' => self::TEST_EMAIL,
      'password' => bcrypt(self::TEST_PASSWORD),
      'session_expires_at' => now()->addDay(), // Sesión activa
    ]);

    // Act
    $response = $this->post('/login', [
      'email' => self::TEST_EMAIL,
      'password' => self::TEST_PASSWORD,
    ]);

    // Assert
    $response->assertRedirect('/login');
    $response->assertSessionHas('session_conflict', true);
    $this->assertGuest();
  }

  /** @test */
  public function puede_solicitar_reset_de_sesion_por_email()
  {
    // Arrange
    $user = User::factory()->create([
      'email' => self::TEST_EMAIL,
      'session_expires_at' => now()->addDay(), // Sesión activa
    ]);

    // Act
    $response = $this->post('/session/reset-email', [
      'email' => self::TEST_EMAIL,
    ]);

    // Assert
    $response->assertJson(['success' => true]);

    // Verificar token en BD
    $this->assertDatabaseHas('session_reset_tokens', [
      'email' => self::TEST_EMAIL,
    ]);

    // Verificar que se intentó enviar email
    Mail::assertSent(\Illuminate\Mail\Mailable::class);
  }

  /** @test */
  public function no_puede_solicitar_reset_sin_sesion_activa()
  {
    // Arrange
    $user = User::factory()->create([
      'email' => self::TEST_EMAIL,
      'session_expires_at' => null, // Sin sesión activa
    ]);

    // Act
    $response = $this->post('/session/reset-email', [
      'email' => self::TEST_EMAIL,
    ]);

    // Assert
    $response->assertJson(['success' => false]);

    // Verificar que NO se creó token
    $this->assertDatabaseMissing('session_reset_tokens', [
      'email' => self::TEST_EMAIL,
    ]);

    Mail::assertNothingSent();
  }

  /** @test */
  public function puede_resetear_sesion_con_token_valido()
  {
    // Arrange
    $user = User::factory()->create([
      'email' => self::TEST_EMAIL,
      'session_expires_at' => now()->addDay(),
    ]);

    $token = 'valid-reset-token';
    $hashedToken = hash('sha256', $token);

    DB::table('session_reset_tokens')->insert([
      'email' => self::TEST_EMAIL,
      'token' => $hashedToken,
      'created_at' => now(),
    ]);

    // Act
    $response = $this->get('/session/reset/' . $token);

    // Assert
    $response->assertRedirect('/login');
    $response->assertSessionHas('session_reset_success');

    // Verificar que la sesión fue eliminada
    $user->refresh();
    $this->assertNull($user->session_expires_at);

    // Verificar que el token fue eliminado
    $this->assertDatabaseMissing('session_reset_tokens', [
      'email' => self::TEST_EMAIL,
    ]);
  }

  /** @test */
  public function rechaza_token_de_reset_invalido()
  {
    // Act
    $response = $this->get('/session/reset/invalid-token');

    // Assert
    $response->assertRedirect('/login');
    $response->assertSessionHas('session_reset_error');
  }

  /** @test */
  public function throttling_bloquea_multiples_intentos_fallidos()
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
        'password' => 'wrong-password',
      ]);
    }

    // Verificar que hay registros de throttling
    $this->assertDatabaseHas('login_attempts', [
      'identifier' => self::TEST_EMAIL,
    ]);

    // Act - Quinto intento (debería estar bloqueado)
    $response = $this->post('/login', [
      'email' => self::TEST_EMAIL,
      'password' => self::TEST_PASSWORD, // Credenciales correctas pero bloqueado
    ]);

    // Assert
    $response->assertSessionHasErrors(['email']);
    $this->assertGuest();
  }

  /** @test */
  public function usuario_autenticado_puede_ver_dashboard()
  {
    // Arrange
    $user = User::factory()->create();

    // Act
    $response = $this->actingAs($user)->get('/dashboard');

    // Assert
    $response->assertOk();
    $response->assertViewIs('dashboard');
    $response->assertViewHas('user', $user);
  }

  /** @test */
  public function usuario_no_autenticado_es_redirigido_al_login()
  {
    // Act
    $response = $this->get('/dashboard');

    // Assert
    $response->assertRedirect('/login');
  }

  /** @test */
  public function usuario_puede_cerrar_sesion()
  {
    // Arrange
    $user = User::factory()->create([
      'session_expires_at' => now()->addDay(),
    ]);

    // Act
    $response = $this->actingAs($user)->post('/logout');

    // Assert
    $response->assertRedirect('/');
    $this->assertGuest();

    // Verificar que la sesión fue limpiada
    $user->refresh();
    $this->assertNull($user->session_expires_at);
  }

  /** @test */
  public function sesion_expira_automaticamente()
  {
    // Arrange
    $user = User::factory()->create([
      'session_expires_at' => now()->subMinutes(5), // Sesión expirada
    ]);

    // Simular que el usuario estaba autenticado
    $this->actingAs($user);

    // Act - Intentar acceder a una ruta protegida
    $response = $this->get('/dashboard');

    // Assert - Debería ser redirigido al login porque la sesión expiró
    $response->assertRedirect('/login');
    $this->assertGuest();
  }

  /** @test */
  public function remember_me_extiende_duracion_sesion()
  {
    // Arrange
    $user = User::factory()->create([
      'email' => self::TEST_EMAIL,
      'password' => bcrypt(self::TEST_PASSWORD),
    ]);

    // Act - Login con "remember me"
    $response = $this->post('/login', [
      'email' => self::TEST_EMAIL,
      'password' => self::TEST_PASSWORD,
      'remember' => '1',
    ]);

    // Assert
    $response->assertRedirect('/dashboard');

    $user->refresh();

    // Verificar que la sesión dura 30 días
    $expectedExpiry = now()->addDays(30);
    $actualExpiry = $user->session_expires_at;

    $this->assertTrue(
      $actualExpiry->diffInMinutes($expectedExpiry) < 5,
      'La sesión con remember me debería durar aproximadamente 30 días'
    );
  }
}
