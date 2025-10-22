<?php

namespace App\Http\Middleware;

use App\Repositories\UserRepository;
use App\Services\SingleSessionManager;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureSingleSession
{
  public function __construct(
    private readonly SingleSessionManager $singleSessionManager,
    private readonly UserRepository $userRepository
  ) {}

  public function handle(Request $request, Closure $next)
  {
    $authUser = Auth::user();

    if ($authUser) {
      $user = $this->userRepository->findById($authUser->id);

      if ($user) {
        $currentToken = $user->getSessionToken();
        $sessionToken = $request->session()->get('session_token');

        if (!$currentToken || !$sessionToken || $currentToken !== $sessionToken) {
          $this->singleSessionManager->clearSession($user);
          Auth::logout();

          $request->session()->invalidate();
          $request->session()->regenerateToken();

          return redirect()->route('login')->withErrors([
            'session' => __('Tu sesión ha sido cerrada porque se detectó un inicio de sesión en otro dispositivo.'),
          ]);
        }
      }
    }

    return $next($request);
  }
}
