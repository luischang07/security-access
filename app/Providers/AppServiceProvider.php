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
    // El throttling de login ahora se maneja en la base de datos
    // Solo mantenemos el throttling de registro
    RateLimiter::for('registration', function (Request $request) {
      $key = $request->ip();

      return Limit::perMinutes(1, 3)
        ->by($key)
        ->response(function (Request $request) {
          return redirect()
            ->route('register')
            ->withErrors([
              'general' => __('Has excedido el número de intentos de registro permitidos. Intenta nuevamente más tarde.'),
            ])
            ->withInput($request->only(['name', 'email']));
        });
    });
  }
}
