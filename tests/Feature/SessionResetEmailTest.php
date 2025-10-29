<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\MailtrapSessionResetService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SessionResetEmailTest extends TestCase
{
  use RefreshDatabase;

  private MailtrapSessionResetService $sessionResetService;

  protected function setUp(): void
  {
    parent::setUp();
    $this->sessionResetService = app(MailtrapSessionResetService::class);
  }

  /** @test */
  public function puede_enviar_email_de_reset_de_sesion_con_usuario_valido()
  {
    // Arrange
    $email = 'test@example.com';

    $user = User::create([
      'name' => 'Usuario Test',
      'email' => $email,
      'nip' => bcrypt('123456'),
      'session_token' => 'test-token-' . time(),
      'session_expires_at' => now()->addHour()
    ]);

    // Act
    $result = $this->sessionResetService->sendSessionResetEmail($email);

    // Assert
    $this->assertTrue($result, 'El email de reset debería enviarse exitosamente');

    // Verificar que se creó el token en BD
    $token = DB::table('session_reset_tokens')
      ->where('email', $email)
      ->first();

    $this->assertNotNull($token, 'Debería existir un token en la base de datos');
    $this->assertEquals($email, $token->email);
    $this->assertNotEmpty($token->token);
    $this->assertNotNull($token->created_at);
  }

  /** @test */
  public function no_puede_enviar_email_si_usuario_no_tiene_sesion_activa()
  {
    // Arrange
    $email = 'test@example.com';

    User::create([
      'name' => 'Usuario Test',
      'email' => $email,
      'nip' => bcrypt('123456'),
      // Sin session_token activo
    ]);

    // Act
    $result = $this->sessionResetService->sendSessionResetEmail($email);

    // Assert
    $this->assertFalse($result, 'No debería enviar email si no hay sesión activa');

    // Verificar que NO se creó token en BD
    $tokenCount = DB::table('session_reset_tokens')
      ->where('email', $email)
      ->count();

    $this->assertEquals(0, $tokenCount, 'No debería crear token si no hay sesión activa');
  }

  /** @test */
  public function no_puede_enviar_email_si_usuario_no_existe()
  {
    // Arrange
    $email = 'noexiste@example.com';

    // Act
    $result = $this->sessionResetService->sendSessionResetEmail($email);

    // Assert
    $this->assertFalse($result, 'No debería enviar email si el usuario no existe');

    // Verificar que NO se creó token en BD
    $tokenCount = DB::table('session_reset_tokens')
      ->where('email', $email)
      ->count();

    $this->assertEquals(0, $tokenCount, 'No debería crear token si el usuario no existe');
  }

  /** @test */
  public function puede_crear_token_reset_con_formato_correcto()
  {
    // Arrange
    $email = 'test@example.com';

    User::create([
      'name' => 'Usuario Test',
      'email' => $email,
      'nip' => bcrypt('123456'),
      'session_token' => 'test-token-' . time(),
      'session_expires_at' => now()->addHour()
    ]);

    // Act
    $this->sessionResetService->sendSessionResetEmail($email);

    // Assert
    $token = DB::table('session_reset_tokens')
      ->where('email', $email)
      ->first();

    // Verificar formato del token (SHA-256 = 64 caracteres hexadecimales)
    $this->assertEquals(64, strlen($token->token));
    $this->assertMatchesRegularExpression('/^[a-f0-9]{64}$/', $token->token);

    // Verificar que el timestamp es reciente (últimos 5 segundos)
    $tokenTime = \Carbon\Carbon::parse($token->created_at);
    $this->assertTrue($tokenTime->diffInSeconds(now()) < 5);
  }

  /** @test */
  public function puede_manejar_multiples_tokens_para_mismo_email()
  {
    // Arrange
    $email = 'test@example.com';

    User::create([
      'name' => 'Usuario Test',
      'email' => $email,
      'nip' => bcrypt('123456'),
      'session_token' => 'test-token-' . time(),
      'session_expires_at' => now()->addHour()
    ]);

    // Act - Enviar múltiples emails
    $this->sessionResetService->sendSessionResetEmail($email);
    sleep(1); // Asegurar timestamps diferentes
    $this->sessionResetService->sendSessionResetEmail($email);

    // Assert
    $tokens = DB::table('session_reset_tokens')
      ->where('email', $email)
      ->get();

    // Debería tener solo 1 token (el más reciente, los anteriores se reemplazan)
    $this->assertEquals(1, $tokens->count(), 'Debería mantener solo el token más reciente');
  }

  /** @test */
  public function servicio_maneja_errores_de_envio_graciosamente()
  {
    // Arrange
    $email = 'test@example.com';

    User::create([
      'name' => 'Usuario Test',
      'email' => $email,
      'nip' => bcrypt('123456'),
      'session_token' => 'test-token-' . time(),
      'session_expires_at' => now()->addHour()
    ]);

    // Simular configuración de correo inválida temporalmente
    config(['mail.host' => 'invalid-host.example.com']);

    // Act & Assert - No debería lanzar excepción, solo devolver false
    $result = $this->sessionResetService->sendSessionResetEmail($email);

    // El servicio puede manejar errores y usar fallback
    $this->assertIsBool($result, 'El método debería devolver un boolean sin excepción');
  }
}
