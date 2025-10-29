<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\SessionResetService;
use Illuminate\Console\Command;

class CleanExpiredSessions extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'sessions:clean-expired';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Limpiar sesiones expiradas de usuarios y tokens de reset';

  public function __construct(
    private readonly SessionResetService $sessionResetService
  ) {
    parent::__construct();
  }

  /**
   * Execute the console command.
   */
  public function handle()
  {
    $this->info('Limpiando sesiones expiradas...');

    $expiredCount = User::where('session_expires_at', '<', now())
      ->whereNotNull('session_token')
      ->update([
        'session_token' => null,
        'session_expires_at' => null
      ]);

    $this->info("Se limpiaron {$expiredCount} sesiones expiradas.");

    $this->info('Limpiando tokens de reset expirados...');
    $expiredTokens = $this->sessionResetService->cleanExpiredTokens();
    $this->info("Se limpiaron {$expiredTokens} tokens de reset expirados.");

    return Command::SUCCESS;
  }
}
