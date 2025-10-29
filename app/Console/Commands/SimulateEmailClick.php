<?php

namespace App\Console\Commands;

use App\Services\MailtrapSessionResetService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SimulateEmailClick extends Command
{
  protected $signature = 'email:simulate-click {email}';
  protected $description = 'Simular clic en enlace de email de reset';

  public function __construct(
    private readonly MailtrapSessionResetService $sessionResetService
  ) {
    parent::__construct();
  }

  public function handle()
  {
    $email = $this->argument('email');

    $this->info("🔗 Simulando clic en enlace para: {$email}");

    $tokenRecord = DB::table('session_reset_tokens')
      ->where('email', $email)
      ->where('created_at', '>', now()->subHours(1))
      ->latest('created_at')
      ->first();

    if (!$tokenRecord) {
      $this->error("❌ No se encontró token válido para {$email}");
      $this->info("💡 Ejecuta primero: php artisan test:session-reset {$email}");
      return Command::FAILURE;
    }

    // Generar token sin hash para simular el enlace
    $originalToken = Str::random(64);
    $hashedToken = hash('sha256', $originalToken);

    // Actualizar el registro con un token conocido para la prueba
    DB::table('session_reset_tokens')
      ->where('email', $email)
      ->update(['token' => $hashedToken]);

    $this->info("🔑 Token de prueba generado");
    $this->info("🌐 URL simulada: " . route('session.reset', $originalToken));

    // Simular el procesamiento del token
    $result = $this->sessionResetService->resetSessionWithToken($originalToken);

    if ($result['success']) {
      $this->info("✅ " . $result['message']);
      $this->info("📧 Email de confirmación enviado a: " . $result['email']);
    } else {
      $this->error("❌ " . $result['message']);
    }

    return Command::SUCCESS;
  }
}
