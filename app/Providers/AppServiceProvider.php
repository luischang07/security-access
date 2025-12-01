<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  public function register(): void
  {
    // Bind RoutingServiceInterface to OsrmRoutingService
    $this->app->bind(
      \App\Services\Routing\RoutingServiceInterface::class,
      \App\Services\Routing\OsrmRoutingService::class
    );
  }

  public function boot(): void
  {
    // El throttling se maneja en la base de datos
  }
}
