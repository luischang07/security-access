<?php

namespace App\Http\Middleware;

use App\Services\SingleSessionManager;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureSingleSession
{
  public function __construct(private readonly SingleSessionManager $singleSessionManager) {}

  public function handle(Request $request, Closure $next)
  {
    $user = Auth::user();

    if ($user) {
      $currentToken = $user->session_token;
      $sessionToken = $request->session()->get('session_token');

      if (!$currentToken || !$sessionToken || $currentToken !== $sessionToken) {
        $this->singleSessionManager->clearSession($user);
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.form')->withErrors([
          'session' => __('Tu sesión ha sido cerrada porque se detectó un inicio de sesión en otro dispositivo.'),
        ]);
      }
    }

    return $next($request);
  }
}
