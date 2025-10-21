<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    RateLimiter::for('login', function (Request $request) {
      $key = $this->loginThrottleKey($request);

      return Limit::perMinutes(30, 3)
        ->by($key)
        ->response(function (Request $request) {
          return redirect()
            ->route('login.form')
            ->withErrors([
              'nip' => __('Has excedido el número de intentos permitidos. Intenta nuevamente más tarde.'),
            ])
            ->withInput($request->only('correo'));
        });
    });
  }

  private function loginThrottleKey(Request $request): string
  {
    $email = (string) $request->input('correo');

    return strtolower($email) . '|' . $request->ip();
  }
}
