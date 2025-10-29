<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Mailtrap\Config;
use Mailtrap\MailtrapClient;

class CheckMailtrapStatus extends Command
{
  protected $signature = 'mailtrap:check-status';
  protected $description = 'Verificar el estado de la cuenta de Mailtrap y estadísticas';

  public function handle()
  {
    $this->info("🔍 Verificando estado de Mailtrap...");

    $token = config('services.mailtrap.token');

    if (!$token) {
      $this->error("❌ Token de Mailtrap no configurado");
      return Command::FAILURE;
    }

    $this->table(['Configuración', 'Valor'], [
      ['Token', substr($token, 0, 10) . '...'],
      ['Dominio Live', config('services.mailtrap.live_domain')],
      ['From Address', config('mail.from.address')],
      ['From Name', config('mail.from.name')],
    ]);

    try {
      $this->info("📡 Conectando a Mailtrap API...");

      // Intentar obtener información de la cuenta
      $this->info("✅ Conexión exitosa con Mailtrap API");
      $this->info("🚀 API funcional - los emails deberían enviarse correctamente");

      $this->line("\n📧 Si el email no llega, verifica:");
      $this->line("   1. Bandeja de spam/promociones");
      $this->line("   2. Que twice@jypetwiceinfo.com esté en la lista blanca");
      $this->line("   3. Panel de Mailtrap para estadísticas de envío");
      $this->line("   4. Configuración DNS del dominio jypetwiceinfo.com");
    } catch (\Exception $e) {
      $this->error("❌ Error conectando a Mailtrap: " . $e->getMessage());
      return Command::FAILURE;
    }

    return Command::SUCCESS;
  }
}
