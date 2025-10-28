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
    // No application services to register at this time.
  }

  public function boot(): void
  {
    // El throttling se maneja en la base de datos
  }
}
