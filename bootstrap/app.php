<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Override bootstrap cache path for Vercel (read-only filesystem)
if (isset($_ENV['APP_BOOTSTRAP_CACHE']) && is_dir($_ENV['APP_BOOTSTRAP_CACHE'])) {
  Application::$bootstrapCachePath = $_ENV['APP_BOOTSTRAP_CACHE'];
}

return Application::configure(basePath: dirname(__DIR__))
  ->withRouting(
    web: __DIR__ . '/../routes/web.php',
    commands: __DIR__ . '/../routes/console.php',
    health: '/up',
  )
  ->withMiddleware(function (Middleware $middleware): void {
    $middleware->alias([
      'single.session' => \App\Http\Middleware\EnsureSingleSession::class,
    ]);
  })
  ->withExceptions(function (): void {
    //
  })->create();
