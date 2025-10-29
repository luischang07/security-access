<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\MailtrapSessionResetService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DebugEmailService extends Command
{
  protected $signature = 'debug:email-service {email=twice@jypetwiceinfo.com}';
  protected $description = 'Debug detallado del servicio de email';

  public function handle()
  {
    $email = $this->argument('email');

    $this->info("🔍 Debug detallado del servicio MailtrapSessionResetService");
    $this->info("📧 Email objetivo: {$email}");

    // Mostrar configuración actual
    $this->line("\n📋 Configuración actual:");
    $this->table(['Config', 'Valor'], [
      ['APP_ENV', config('app.env')],
      ['MAIL_MAILER', config('mail.default')],
      ['MAIL_HOST', config('mail.host')],
      ['MAIL_PORT', config('mail.port')],
      ['MAIL_FROM_ADDRESS', config('mail.from.address')],
      ['MAIL_FROM_NAME', config('mail.from.name')],
      ['MAILTRAP_TOKEN', config('services.mailtrap.token') ? 'Configurado' : 'NO configurado'],
    ]);

    // Preparar usuario
    $user = User::updateOrCreate(
      ['email' => $email],
      [
        'name' => 'Debug User',
        'nip' => bcrypt('123456'),
        'session_token' => 'debug-' . now()->timestamp,
        'session_expires_at' => now()->addHour()
      ]
    );

    $this->line("\n👤 Usuario preparado: {$user->name}");

    // Instanciar el servicio
    $service = app(MailtrapSessionResetService::class);

    // Capturar logs en tiempo real
    $this->line("\n📤 Ejecutando sendSessionResetEmail()...");

    // Limpiar logs anteriores
    Log::info("=== INICIO DEBUG EMAIL SERVICE ===");

    try {
      $result = $service->sendSessionResetEmail($email);

      $this->line("📊 Resultado: " . ($result ? 'TRUE (exitoso)' : 'FALSE (falló)'));

      // Verificar si se creó el token
      $token = DB::table('session_reset_tokens')
        ->where('email', $email)
        ->latest('created_at')
        ->first();

      if ($token) {
        $this->info("✅ Token creado en BD: " . substr($token->token, 0, 16) . "...");
      } else {
        $this->error("❌ No se creó token en BD");
      }
    } catch (\Exception $e) {
      $this->error("💥 Excepción capturada: " . $e->getMessage());
      $this->error("📍 En: " . $e->getFile() . ':' . $e->getLine());
    }

    Log::info("=== FIN DEBUG EMAIL SERVICE ===");

    $this->line("\n📝 Revisa storage/logs/laravel.log para logs detallados");

    return Command::SUCCESS;
  }
}
