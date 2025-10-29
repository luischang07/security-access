<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ConfigureMailtrap extends Command
{
  protected $signature = 'mailtrap:configure {--mode=sandbox : Modo de configuración (sandbox|live)}';
  protected $description = 'Configurar Mailtrap para desarrollo o producción';

  public function handle()
  {
    $mode = $this->option('mode');

    $this->info("🔧 Configurando Mailtrap en modo: {$mode}");

    if ($mode === 'sandbox') {
      $this->configureSandbox();
    } elseif ($mode === 'live') {
      $this->configureLive();
    } else {
      $this->error('Modo no válido. Usa: sandbox o live');
      return Command::FAILURE;
    }

    return Command::SUCCESS;
  }

  private function configureSandbox()
  {
    $this->info('📧 Configurando Mailtrap Sandbox (Desarrollo)');

    $username = $this->ask('Username de Mailtrap Sandbox:');
    $password = $this->ask('Password de Mailtrap Sandbox:');
    $apiToken = $this->ask('API Token (opcional):') ?? 'not_configured';
    $inboxId = $this->ask('Inbox ID (opcional):') ?? 'not_configured';

    $this->updateEnvFile([
      'MAIL_MAILER' => 'smtp',
      'MAIL_HOST' => 'sandbox.smtp.mailtrap.io',
      'MAIL_PORT' => '2525',
      'MAIL_USERNAME' => $username,
      'MAIL_PASSWORD' => $password,
      'MAIL_ENCRYPTION' => 'tls',
      'MAILTRAP_API_TOKEN' => $apiToken,
      'MAILTRAP_SANDBOX_INBOX_ID' => $inboxId,
    ]);

    $this->info('✅ Configuración de sandbox completada!');
    $this->info('🧪 Prueba con: php artisan test:session-reset');
    $this->info('👀 Ve los emails en: https://mailtrap.io/inboxes');
  }

  private function configureLive()
  {
    $this->info('🚀 Configurando Mailtrap Live (Producción)');

    $this->warn('⚠️  Asegúrate de haber verificado tu dominio en Mailtrap primero!');

    $apiToken = $this->ask('API Token de Mailtrap:');
    $domain = $this->ask('Tu dominio verificado (ej: tuapp.com):');
    $fromEmail = $this->ask("Email FROM (ej: security@{$domain}):");

    $this->updateEnvFile([
      'MAIL_MAILER' => 'smtp',
      'MAIL_HOST' => 'live.smtp.mailtrap.io',
      'MAIL_PORT' => '587',
      'MAIL_USERNAME' => 'api',
      'MAIL_PASSWORD' => $apiToken,
      'MAIL_ENCRYPTION' => 'tls',
      'MAIL_FROM_ADDRESS' => $fromEmail,
      'MAILTRAP_API_TOKEN' => $apiToken,
      'MAILTRAP_LIVE_DOMAIN' => $domain,
    ]);

    $this->info('✅ Configuración de producción completada!');
    $this->info('🧪 Prueba con: php artisan test:session-reset tu-email@real.com');
    $this->info('📊 Ve estadísticas en: https://mailtrap.io/sending');
  }

  private function updateEnvFile(array $values)
  {
    $envPath = base_path('.env');
    $envContent = File::get($envPath);

    foreach ($values as $key => $value) {
      $pattern = "/^{$key}=.*/m";
      $replacement = "{$key}={$value}";

      if (preg_match($pattern, $envContent)) {
        $envContent = preg_replace($pattern, $replacement, $envContent);
      } else {
        $envContent .= "\n{$replacement}";
      }
    }

    File::put($envPath, $envContent);

    // Limpiar cache de configuración
    $this->call('config:cache');
  }
}
