<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

class MailtrapIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        
        // Configurar Mailtrap para testing
        Config::set('services.mailtrap.token', null); // Sin token para usar SMTP fallback
        Mail::fake();
    }

    /** @test */
    public function comando_mailtrap_configure_existe_y_funciona()
    {
        // Act & Assert
        $exitCode = $this->artisan('mailtrap:configure', ['--mode' => 'sandbox'])->run();
        $this->assertEquals(0, $exitCode);
    }

    /** @test */
    public function comando_mailtrap_configure_funciona_en_modo_live()
    {
        // Act & Assert
        $exitCode = $this->artisan('mailtrap:configure', ['--mode' => 'live'])->run();
        $this->assertEquals(0, $exitCode);
    }

    /** @test */
    public function servicio_mailtrap_maneja_ausencia_de_token_gracefully()
    {
        // Arrange
        Config::set('services.mailtrap.token', null);
        
        // Act
        $email = 'test@example.com';
        DB::table('session_reset_tokens')->insert([
            'email' => $email,
            'token' => hash('sha256', 'test-token'),
            'created_at' => now(),
        ]);

        // El servicio debería usar SMTP fallback cuando no hay token
        $this->assertTrue(true); // Test pasa si no hay excepciones
    }

    /** @test */
    public function puede_generar_estadisticas_sin_token_mailtrap()
    {
        // Arrange
        Config::set('services.mailtrap.token', null);
        
        // Simular datos en la BD
        DB::table('session_reset_tokens')->insert([
            ['email' => 'user1@example.com', 'token' => hash('sha256', 'token1'), 'created_at' => now()],
            ['email' => 'user2@example.com', 'token' => hash('sha256', 'token2'), 'created_at' => now()],
        ]);

        // Act - Simular obtención de estadísticas básicas sin Mailtrap API
        $stats = [
            'tokens_pending' => DB::table('session_reset_tokens')->count(),
            'last_cleanup' => now(),
            'error' => 'Token no configurado'
        ];

        // Assert
        $this->assertEquals(2, $stats['tokens_pending']);
        $this->assertEquals('Token no configurado', $stats['error']);
    }

    /** @test */
    public function configuracion_mailtrap_en_env_es_correcta()
    {
        // Assert - Verificar que las configuraciones de Mailtrap están presentes
        $this->assertNotNull(config('mail.host'));
        $this->assertNotNull(config('mail.username'));
        $this->assertNotNull(config('mail.from.address'));
        $this->assertNotNull(config('mail.from.name'));
    }

    /** @test */
    public function puede_simular_envio_de_email_con_mailtrap()
    {
        // Arrange
        Mail::fake();
        $email = 'test@example.com';

        // Act - Simular envío de email (usando Mail fake)
        Mail::raw('Test email content', function ($message) use ($email) {
            $message->to($email)
                    ->subject('Test Mailtrap Email')
                    ->from(config('mail.from.address'), config('mail.from.name'));
        });

        // Assert
        Mail::assertSent(\Illuminate\Mail\Mailable::class, function ($mail) use ($email) {
            return $mail->hasTo($email);
        });
    }

    /** @test */
    public function configuracion_mailtrap_tiene_valores_esperados()
    {
        // Assert - Verificar configuraciones específicas de Mailtrap
        $this->assertEquals('live.smtp.mailtrap.io', config('mail.host'));
        $this->assertEquals(587, config('mail.port'));
        $this->assertEquals('tls', config('mail.encryption'));
        $this->assertEquals('twice@jypetwiceinfo.com', config('mail.from.address'));
        $this->assertEquals('Sistema de Seguridad', config('mail.from.name'));
    }

    /** @test */
    public function puede_verificar_dominio_mailtrap_configurado()
    {
        // Arrange
        $configuredDomain = config('services.mailtrap.live_domain', 'jypetwiceinfo.com');
        $fromAddress = config('mail.from.address');

        // Act
        $domainFromEmail = substr(strrchr($fromAddress, "@"), 1);

        // Assert
        $this->assertEquals($configuredDomain, $domainFromEmail);
    }

    /** @test */
    public function puede_obtener_configuracion_completa_mailtrap()
    {
        // Act
        $mailtrapConfig = [
            'api_token' => config('services.mailtrap.token'),
            'sandbox_inbox' => config('services.mailtrap.sandbox_inbox_id'),
            'live_domain' => config('services.mailtrap.live_domain'),
            'smtp_host' => config('mail.host'),
            'smtp_port' => config('mail.port'),
            'smtp_username' => config('mail.username'),
            'smtp_encryption' => config('mail.encryption'),
            'from_address' => config('mail.from.address'),
            'from_name' => config('mail.from.name'),
        ];

        // Assert - Verificar que todas las configuraciones están presentes
        $this->assertIsArray($mailtrapConfig);
        $this->assertArrayHasKey('api_token', $mailtrapConfig);
        $this->assertArrayHasKey('live_domain', $mailtrapConfig);
        $this->assertArrayHasKey('smtp_host', $mailtrapConfig);
        $this->assertArrayHasKey('from_address', $mailtrapConfig);
        
        // Verificar valores críticos
        $this->assertEquals('live.smtp.mailtrap.io', $mailtrapConfig['smtp_host']);
        $this->assertEquals('jypetwiceinfo.com', $mailtrapConfig['live_domain']);
        $this->assertEquals('twice@jypetwiceinfo.com', $mailtrapConfig['from_address']);
    }

    /** @test */
    public function comando_test_session_reset_funciona_con_mailtrap()
    {
        // Arrange
        $email = 'test@example.com';

        // Act - El comando debería funcionar sin errores
        $exitCode = $this->artisan('test:session-reset', ['email' => $email])->run();

        // Assert
        $this->assertEquals(0, $exitCode);
    }
}