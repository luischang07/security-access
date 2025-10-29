<?php

namespace App\Console\Commands;

use App\Services\SupabaseService;
use Illuminate\Console\Command;

class SupabaseSetup extends Command
{
  protected $signature = 'supabase:setup';
  protected $description = 'Configurar y verificar conexión con Supabase';

  public function __construct(
    private readonly SupabaseService $supabase
  ) {
    parent::__construct();
  }

  public function handle()
  {
    $this->info("🔧 === CONFIGURACIÓN DE SUPABASE ===");

    // Paso 1: Verificar configuración
    $this->checkConfig();

    // Paso 2: Probar conexión
    $this->testConnection();

    // Paso 3: Crear tablas en Supabase si es necesario
    $this->setupTables();

    $this->info("\n✅ ¡Configuración de Supabase completada!");

    return Command::SUCCESS;
  }

  private function checkConfig()
  {
    $this->info("\n1️⃣ Verificando configuración...");

    $url = config('services.supabase.url');
    $anonKey = config('services.supabase.anon_key');
    $serviceKey = config('services.supabase.service_key');

    if (!$url || $url === 'https://your-project.supabase.co') {
      $this->error("❌ SUPABASE_URL no configurada");
      $this->askForCredentials();
      return;
    }

    if (!$anonKey || $anonKey === 'your-anon-key-here') {
      $this->error("❌ SUPABASE_ANON_KEY no configurada");
      $this->askForCredentials();
      return;
    }

    if (!$serviceKey || $serviceKey === 'your-service-role-key-here') {
      $this->error("❌ SUPABASE_SERVICE_KEY no configurada");
      $this->askForCredentials();
      return;
    }

    $this->info("✅ Configuración encontrada");
    $this->info("   URL: {$url}");
    $this->info("   Anon Key: " . substr($anonKey, 0, 10) . "...");
  }

  private function askForCredentials()
  {
    $this->newLine();
    $this->warn("🔑 Necesitas configurar tus credenciales de Supabase:");
    $this->info("1. Ve a https://supabase.com/dashboard");
    $this->info("2. Crea un nuevo proyecto o abre uno existente");
    $this->info("3. Ve a Settings > API");
    $this->info("4. Copia las credenciales a tu archivo .env");
    $this->newLine();

    if ($this->confirm("¿Quieres que te guíe para configurarlas ahora?")) {
      $url = $this->ask("URL del proyecto (ej: https://abc123.supabase.co):");
      $anonKey = $this->ask("Anon Key (clave pública):");
      $serviceKey = $this->ask("Service Role Key (clave privada):");

      $this->updateEnvFile($url, $anonKey, $serviceKey);
    }
  }

  private function updateEnvFile($url, $anonKey, $serviceKey)
  {
    $envPath = base_path('.env');
    $envContent = file_get_contents($envPath);

    $envContent = preg_replace(
      '/SUPABASE_URL=.*/',
      "SUPABASE_URL={$url}",
      $envContent
    );

    $envContent = preg_replace(
      '/SUPABASE_ANON_KEY=.*/',
      "SUPABASE_ANON_KEY={$anonKey}",
      $envContent
    );

    $envContent = preg_replace(
      '/SUPABASE_SERVICE_KEY=.*/',
      "SUPABASE_SERVICE_KEY={$serviceKey}",
      $envContent
    );

    file_put_contents($envPath, $envContent);
    $this->info("✅ Archivo .env actualizado");
    $this->warn("⚠️  Reinicia el servidor para aplicar cambios");
  }

  private function testConnection()
  {
    $this->info("\n2️⃣ Probando conexión...");

    try {
      $result = $this->supabase->select('users', [], ['id']);

      if ($result !== null) {
        $this->info("✅ Conexión exitosa con Supabase");
      } else {
        $this->error("❌ Error en la conexión");
      }
    } catch (\Exception $e) {
      $this->error("❌ Error: " . $e->getMessage());
    }
  }

  private function setupTables()
  {
    $this->info("\n3️⃣ Configurando tablas...");
    $this->info("📝 SQL para crear tablas en Supabase:");

    $sql = "
-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS users (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    nip VARCHAR(255) NOT NULL,
    session_token VARCHAR(255),
    session_expires_at TIMESTAMPTZ,
    last_login_at TIMESTAMPTZ,
    login_attempts INTEGER DEFAULT 0,
    login_attempts_reset_at TIMESTAMPTZ,
    locked_until TIMESTAMPTZ,
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW()
);

-- Tabla de tokens de reset
CREATE TABLE IF NOT EXISTS session_reset_tokens (
    email VARCHAR(255) PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMPTZ DEFAULT NOW()
);

-- Política de seguridad (RLS)
ALTER TABLE users ENABLE ROW LEVEL SECURITY;
ALTER TABLE session_reset_tokens ENABLE ROW LEVEL SECURITY;

-- Triggers para updated_at
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = NOW();
    RETURN NEW;
END;
$$ language 'plpgsql';

CREATE TRIGGER update_users_updated_at BEFORE UPDATE ON users
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
        ";

    $this->line($sql);
    $this->newLine();
    $this->info("📋 Copia este SQL y ejecútalo en el SQL Editor de Supabase");
    $this->info("   Dashboard > SQL Editor > New query");
  }
}
