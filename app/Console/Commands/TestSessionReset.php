<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\MailtrapSessionResetService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TestSessionReset extends Command
{
  protected $signature = 'test:session-reset {email}';
  protected $description = 'Preparar usuario para test de session reset';

  public function __construct(
    private readonly MailtrapSessionResetService $sessionResetService
  ) {
    parent::__construct();
  }

  public function handle()
  {
    $email = $this->argument('email');

    $this->info("🧪 Preparando test de session reset para: {$email}");

    // Crear usuario con sesión activa
    $user = User::updateOrCreate(
      ['email' => $email],
      [
        'name' => 'Usuario Test Session Reset - ' . now()->format('H:i:s'),
        'nip' => bcrypt('NipSeguro123!'),
        'session_token' => 'test-session-' . now()->timestamp,
        'session_expires_at' => now()->addHour(),
        'login_attempts' => 0,
        'locked_until' => null,
        'last_login_at' => now()
      ]
    );

    $this->info("✅ Usuario preparado:");
    $this->info("   - Email: {$user->email}");
    $this->info("   - NIP: NipSeguro123!");
    $this->info("   - Session Token: {$user->session_token}");

    // Enviar email de reset
    $this->info("\n📧 Enviando email de session reset...");

    $emailSent = $this->sessionResetService->sendSessionResetEmail($email);

    if ($emailSent) {
      $this->info("✅ Email enviado exitosamente!");

      $tokenRecord = DB::table('session_reset_tokens')
        ->where('email', $email)
        ->latest('created_at')
        ->first();

      if ($tokenRecord) {
        $this->info("🔑 Token creado: " . substr($tokenRecord->token, 0, 16) . '...');
        $this->info("\n🎯 Ahora puedes ejecutar:");
        $this->info("   php artisan email:simulate-click {$email}");
      }
    } else {
      $this->error("❌ No se pudo enviar el email");
    }

    return Command::SUCCESS;
  }
}
