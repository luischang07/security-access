<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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
  public function boot()
  {
    Schema::defaultStringLength(191);
  }
}
