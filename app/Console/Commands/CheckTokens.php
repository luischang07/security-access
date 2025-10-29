<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckTokens extends Command
{
  protected $signature = 'tokens:check {email?}';
  protected $description = 'Verificar tokens de reset de sesión';

  public function handle()
  {
    $email = $this->argument('email') ?? 'twice@jypetwiceinfo.com';

    $this->info("🔍 Verificando tokens para: {$email}");

    $tokens = DB::table('session_reset_tokens')
      ->where('email', $email)
      ->orderBy('created_at', 'desc')
      ->get();

    if ($tokens->isEmpty()) {
      $this->warn("❌ No se encontraron tokens para {$email}");
      return;
    }

    foreach ($tokens as $token) {
      $isExpired = now()->diffInHours($token->created_at) > 1;
      $status = $isExpired ? '❌ EXPIRADO' : '✅ VÁLIDO';

      $this->info("📧 Email: {$token->email}");
      $this->info("🕒 Creado: {$token->created_at}");
      $this->info("⏰ Estado: {$status}");
      $this->info("🔑 Hash: " . substr($token->token, 0, 20) . '...');
      $this->line('---');
    }

    $this->info("📊 Total tokens: " . $tokens->count());
  }
}
