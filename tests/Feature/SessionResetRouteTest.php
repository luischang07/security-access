<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SessionResetRouteTest extends TestCase
{
  use RefreshDatabase;

  /** @test */
  public function ruta_session_reset_send_funciona_correctamente()
  {
    // Arrange
    Mail::fake();

    $user = User::create([
      'name' => 'Test User',
      'email' => 'twice@jypetwiceinfo.com',
      'nip' => bcrypt('123456'),
      'session_token' => 'test-session-' . time(),
      'session_expires_at' => now()->addHour()
    ]);

    // Act
    $response = $this->post('/session/reset/send', [
      'email' => 'twice@jypetwiceinfo.com'
    ]);

    // Assert
    $response->assertRedirect();
    $response->assertSessionHas('status');

    // Verificar que se creó el token en la BD
    $this->assertDatabaseHas('session_reset_tokens', [
      'email' => 'twice@jypetwiceinfo.com'
    ]);

    $this->assertTrue(true, 'La ruta /session/reset/send funciona correctamente');
  }

  /** @test */
  public function ruta_rechaza_email_sin_sesion_activa()
  {
    // Arrange
    User::create([
      'name' => 'Test User',
      'email' => 'test@example.com',
      'nip' => bcrypt('123456'),
      // Sin session_token
    ]);

    // Act
    $response = $this->post('/session/reset/send', [
      'email' => 'test@example.com'
    ]);

    // Assert
    $response->assertRedirect();
    $response->assertSessionHasErrors(['email']);

    // Verificar que NO se creó token en la BD
    $this->assertDatabaseMissing('session_reset_tokens', [
      'email' => 'test@example.com'
    ]);
  }

  /** @test */
  public function puede_probar_envio_real_a_mailtrap()
  {
    // Este test enviará un email real a Mailtrap
    $user = User::create([
      'name' => 'Test User Real',
      'email' => 'twice@jypetwiceinfo.com',
      'nip' => bcrypt('123456'),
      'session_token' => 'real-test-' . time(),
      'session_expires_at' => now()->addHour()
    ]);

    // Act - Sin fake de Mail para envío real
    $response = $this->post('/session/reset/send', [
      'email' => 'twice@jypetwiceinfo.com'
    ]);

    // Assert
    $response->assertRedirect();
    $response->assertSessionHas('status', 'Se ha enviado un correo electrónico con instrucciones para eliminar tu sesión activa.');

    // Verificar que se creó el token en la BD
    $this->assertDatabaseHas('session_reset_tokens', [
      'email' => 'twice@jypetwiceinfo.com'
    ]);

    // Información para el usuario
    echo "\n✅ Email enviado a twice@jypetwiceinfo.com través de la ruta web\n";
  }
}
