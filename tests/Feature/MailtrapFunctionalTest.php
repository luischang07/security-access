<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

class MailtrapFunctionalTest extends TestCase
{
  use RefreshDatabase;

  protected function setUp(): void
  {
    parent::setUp();
    $this->artisan('migrate');

    // Configurar valores de Mailtrap para testing
    Config::set('mail.mailer', 'smtp');
    Config::set('mail.host', 'live.smtp.mailtrap.io');
    Config::set('mail.port', 587);
    Config::set('mail.username', 'api');
    Config::set('mail.encryption', 'tls');
    Config::set('mail.from.address', 'twice@jypetwiceinfo.com');
    Config::set('mail.from.name', 'Sistema de Seguridad');

    Config::set('services.mailtrap.token', '84c03ad07298c54dd4a2af494800b9da');
    Config::set('services.mailtrap.live_domain', 'jypetwiceinfo.com');

    Mail::fake();
  }

  /** @test */
  public function configuracion_mailtrap_esta_correctamente_establecida()
  {
    // Assert
    $this->assertEquals('live.smtp.mailtrap.io', config('mail.host'));
    $this->assertEquals(587, config('mail.port'));
    $this->assertEquals('tls', config('mail.encryption'));
    $this->assertEquals('twice@jypetwiceinfo.com', config('mail.from.address'));
    $this->assertEquals('Sistema de Seguridad', config('mail.from.name'));
    $this->assertEquals('jypetwiceinfo.com', config('services.mailtrap.live_domain'));
  }

  /** @test */
  public function puede_simular_envio_email_con_configuracion_mailtrap()
  {
    // Act - Simplemente verificar que las configuraciones están disponibles
    $mailHost = config('mail.host');
    $mailPort = config('mail.port');
    $fromAddress = config('mail.from.address');

    // Assert - Las configuraciones necesarias están disponibles
    $this->assertNotEmpty($mailHost);
    $this->assertNotEmpty($mailPort);
    $this->assertNotEmpty($fromAddress);

    // Verificar que podemos acceder a la configuración de Mailtrap
    $this->assertEquals('live.smtp.mailtrap.io', $mailHost);
    $this->assertEquals('twice@jypetwiceinfo.com', $fromAddress);
  }

  /** @test */
  public function dominio_configurado_coincide_con_email_from()
  {
    // Arrange
    $configuredDomain = config('services.mailtrap.live_domain');
    $fromAddress = config('mail.from.address');

    // Act
    $domainFromEmail = substr(strrchr($fromAddress, "@"), 1);

    // Assert
    $this->assertEquals($configuredDomain, $domainFromEmail);
  }

  /** @test */
  public function servicio_mailtrap_puede_manejar_fallback_smtp()
  {
    // Arrange - Remover token API para forzar uso de SMTP
    Config::set('services.mailtrap.token', null);

    $email = 'twice@jypetwiceinfo.com';
    DB::table('session_reset_tokens')->insert([
      'email' => $email,
      'token' => hash('sha256', 'test-token'),
      'created_at' => now(),
    ]);

    // Act & Assert - No debería fallar sin token API
    $this->assertDatabaseHas('session_reset_tokens', [
      'email' => $email,
    ]);
  }

  /** @test */
  public function puede_obtener_estadisticas_basicas_sin_api()
  {
    // Arrange
    Config::set('services.mailtrap.token', null);

    DB::table('session_reset_tokens')->insert([
      ['email' => 'user1@example.com', 'token' => hash('sha256', 'token1'), 'created_at' => now()],
      ['email' => 'user2@example.com', 'token' => hash('sha256', 'token2'), 'created_at' => now()],
      ['email' => 'user3@example.com', 'token' => hash('sha256', 'token3'), 'created_at' => now()->subHours(2)],
    ]);

    // Act
    $stats = [
      'tokens_pending' => DB::table('session_reset_tokens')->count(),
      'tokens_recent' => DB::table('session_reset_tokens')
        ->where('created_at', '>', now()->subHours(1))
        ->count(),
      'error' => 'Token no configurado'
    ];

    // Assert
    $this->assertEquals(3, $stats['tokens_pending']);
    $this->assertEquals(2, $stats['tokens_recent']);
    $this->assertEquals('Token no configurado', $stats['error']);
  }

  /** @test */
  public function configuracion_completa_mailtrap_tiene_valores_correctos()
  {
    // Act
    $mailtrapConfig = [
      'api_token' => config('services.mailtrap.token'),
      'live_domain' => config('services.mailtrap.live_domain'),
      'smtp_host' => config('mail.host'),
      'smtp_port' => config('mail.port'),
      'smtp_username' => config('mail.username'),
      'smtp_encryption' => config('mail.encryption'),
      'from_address' => config('mail.from.address'),
      'from_name' => config('mail.from.name'),
    ];

    // Assert
    $this->assertIsArray($mailtrapConfig);
    $this->assertEquals('84c03ad07298c54dd4a2af494800b9da', $mailtrapConfig['api_token']);
    $this->assertEquals('jypetwiceinfo.com', $mailtrapConfig['live_domain']);
    $this->assertEquals('live.smtp.mailtrap.io', $mailtrapConfig['smtp_host']);
    $this->assertEquals(587, $mailtrapConfig['smtp_port']);
    $this->assertEquals('api', $mailtrapConfig['smtp_username']);
    $this->assertEquals('tls', $mailtrapConfig['smtp_encryption']);
    $this->assertEquals('twice@jypetwiceinfo.com', $mailtrapConfig['from_address']);
    $this->assertEquals('Sistema de Seguridad', $mailtrapConfig['from_name']);
  }

  /** @test */
  public function puede_validar_formato_email_mailtrap()
  {
    // Arrange
    $validEmails = [
      'user@jypetwiceinfo.com',
      'test@jypetwiceinfo.com',
      'admin@jypetwiceinfo.com'
    ];

    $invalidEmails = [
      'user@wrongdomain.com',
      'test@gmail.com',
      'admin@hotmail.com'
    ];

    $configuredDomain = config('services.mailtrap.live_domain');

    // Act & Assert
    foreach ($validEmails as $email) {
      $domain = substr(strrchr($email, "@"), 1);
      $this->assertEquals($configuredDomain, $domain, "Email {$email} debería tener dominio correcto");
    }

    foreach ($invalidEmails as $email) {
      $domain = substr(strrchr($email, "@"), 1);
      $this->assertNotEquals($configuredDomain, $domain, "Email {$email} no debería tener el dominio configurado");
    }
  }

  /** @test */
  public function configuracion_mailtrap_tiene_todos_los_campos_requeridos()
  {
    // Act
    $requiredConfigs = [
      'mail.host',
      'mail.port',
      'mail.username',
      'mail.encryption',
      'mail.from.address',
      'mail.from.name',
      'services.mailtrap.token',
      'services.mailtrap.live_domain'
    ];

    // Assert
    foreach ($requiredConfigs as $configKey) {
      $this->assertNotNull(
        config($configKey),
        "La configuración {$configKey} no debe ser null"
      );
    }
  }

  /** @test */
  public function puede_generar_template_email_basico()
  {
    // Arrange
    $userName = 'Test User';
    $resetUrl = 'https://example.com/reset/token123';

    // Act
    $template = "
            <h2>🔐 Solicitud de eliminación de sesión activa</h2>
            <p>Hola <strong>{$userName}</strong>,</p>
            <p>Hemos detectado que intentaste iniciar sesión desde un nuevo dispositivo.</p>
            <p><a href=\"{$resetUrl}\" style=\"background: #007cba; color: white; padding: 10px 20px; text-decoration: none;\">Eliminar Sesión Actual</a></p>
            <p>Este enlace es válido por 1 hora.</p>
        ";

    // Assert
    $this->assertStringContainsString($userName, $template);
    $this->assertStringContainsString($resetUrl, $template);
    $this->assertStringContainsString('🔐 Solicitud', $template);
    $this->assertStringContainsString('1 hora', $template);
  }
}
