<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\MailtrapSessionResetService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SendTestEmail extends Command
{
  protected $signature = 'email:test-send {email=twice@jypetwiceinfo.com}';
  protected $description = 'Enviar email de prueba de reset de sesión a un email específico';

  public function __construct(
    private readonly MailtrapSessionResetService $sessionResetService
  ) {
    parent::__construct();
  }

  public function handle()
  {
    $email = $this->argument('email');

    $this->info("🚀 Enviando email de prueba a: {$email}");
    $this->info("📧 Usando configuración de Mailtrap Live");

    // Crear o actualizar usuario de prueba
    $user = User::updateOrCreate(
      ['email' => $email],
      [
        'name' => 'Usuario Test - ' . now()->format('H:i:s'),
        'nip' => bcrypt('123456'),
        'session_token' => 'test-session-' . now()->timestamp,
        'session_expires_at' => now()->addHour()
      ]
    );

    $this->line("👤 Usuario preparado: {$user->name}");
    $this->line("🔑 Token de sesión: {$user->session_token}");

    // Enviar el email
    try {
      $this->info("📤 Enviando email...");

      $result = $this->sessionResetService->sendSessionResetEmail($email);

      if ($result) {
        $this->info("✅ Email enviado exitosamente!");

        // Obtener el token generado
        $resetToken = DB::table('session_reset_tokens')
          ->where('email', $email)
          ->latest('created_at')
          ->first();

        if ($resetToken) {
          $this->table(
            ['Campo', 'Valor'],
            [
              ['Email', $resetToken->email],
              ['Token (primeros 16)', substr($resetToken->token, 0, 16) . '...'],
              ['Creado', $resetToken->created_at],
              ['Válido hasta', now()->parse($resetToken->created_at)->addHour()->format('Y-m-d H:i:s')],
            ]
          );

          $resetUrl = route('session.reset', $resetToken->token);
          $this->line("🔗 URL de reset: {$resetUrl}");
        }

        $this->info("📧 Revisa tu bandeja de entrada en: {$email}");
        $this->info("📝 También puedes revisar logs: storage/logs/laravel.log");
      } else {
        $this->error("❌ No se pudo enviar el email");
        $this->error("⚠️  Posibles causas:");
        $this->error("   - Usuario no tiene sesión activa");
        $this->error("   - Error en configuración de correo");
        $this->error("   - Problemas de conectividad");
      }
    } catch (\Exception $e) {
      $this->error("💥 Error al enviar email: " . $e->getMessage());
      $this->error("🔧 Verifica tu configuración en .env");

      if ($this->option('verbose')) {
        $this->error("Stack trace:");
        $this->error($e->getTraceAsString());
      }
    }

    return Command::SUCCESS;
  }
}
