<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\SupabaseService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateToSupabase extends Command
{
  protected $signature = 'supabase:migrate {--force : Force migration without confirmation}';
  protected $description = 'Migrar datos existentes de MySQL a Supabase PostgreSQL';

  public function __construct(
    private readonly SupabaseService $supabase
  ) {
    parent::__construct();
  }

  public function handle()
  {
    $this->info("🚀 === MIGRACIÓN A SUPABASE ===");

    // Verificar configuración
    if (!config('services.supabase.url')) {
      $this->error("❌ Configuración de Supabase no encontrada en .env");
      $this->info("💡 Asegúrate de configurar SUPABASE_URL, SUPABASE_ANON_KEY, etc.");
      return Command::FAILURE;
    }

    $this->info("📡 URL de Supabase: " . config('services.supabase.url'));

    if (!$this->option('force')) {
      if (!$this->confirm('¿Continuar con la migración?')) {
        $this->info("Migración cancelada.");
        return Command::SUCCESS;
      }
    }

    $this->newLine();
    $this->info("📊 Analizando datos existentes...");

    // Migrar usuarios
    $this->migrateUsers();

    // Migrar tokens de reset
    $this->migrateResetTokens();

    $this->newLine();
    $this->info("✅ ¡Migración completada!");
    $this->info("🔧 Para usar Supabase como base de datos principal:");
    $this->info("   1. Descomenta las líneas de PostgreSQL en .env");
    $this->info("   2. Comenta las líneas de MySQL en .env");
    $this->info("   3. Ejecuta: php artisan migrate");

    return Command::SUCCESS;
  }

  private function migrateUsers()
  {
    $users = User::all();
    $this->info("👥 Migrando {$users->count()} usuarios...");

    $migrated = 0;
    foreach ($users as $user) {
      $userData = [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'nip' => $user->nip,
        'session_token' => $user->session_token,
        'session_expires_at' => $user->session_expires_at?->toISOString(),
        'last_login_at' => $user->last_login_at?->toISOString(),
        'login_attempts' => $user->login_attempts,
        'login_attempts_reset_at' => $user->login_attempts_reset_at?->toISOString(),
        'locked_until' => $user->locked_until?->toISOString(),
        'created_at' => $user->created_at->toISOString(),
        'updated_at' => $user->updated_at->toISOString(),
      ];

      $result = $this->supabase->insert('users', $userData);

      if ($result) {
        $migrated++;
        $this->line("  ✅ {$user->email}");
      } else {
        $this->line("  ❌ {$user->email} - Error en migración");
      }
    }

    $this->info("📈 Usuarios migrados: {$migrated}/{$users->count()}");
  }

  private function migrateResetTokens()
  {
    $tokens = DB::table('session_reset_tokens')->get();
    $this->info("🔑 Migrando {$tokens->count()} tokens de reset...");

    $migrated = 0;
    foreach ($tokens as $token) {
      $tokenData = [
        'email' => $token->email,
        'token' => $token->token,
        'created_at' => $token->created_at,
      ];

      $result = $this->supabase->insert('session_reset_tokens', $tokenData);

      if ($result) {
        $migrated++;
        $this->line("  ✅ {$token->email}");
      } else {
        $this->line("  ❌ {$token->email} - Error en migración");
      }
    }

    $this->info("📈 Tokens migrados: {$migrated}/{$tokens->count()}");
  }
}
