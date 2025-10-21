<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Services\SingleSessionManager;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{
  public function __construct(private readonly SingleSessionManager $singleSessionManager) {}

  public function showLoginForm(): View
  {
    return view('auth.login');
  }

  public function login(LoginRequest $request): RedirectResponse
  {
    $user = User::where('email', $request->correo)->first();

    if ($user && Hash::check($request->nip, $user->nip)) {
      RateLimiter::clear($this->throttleKey($request));

      $sessionToken = Str::uuid()->toString();
      $this->singleSessionManager->registerSession($user, $sessionToken);

      Auth::login($user);

      $request->session()->put('session_token', $sessionToken);
      $request->session()->regenerate();

      return redirect()->route('landing')->with('status', __('Bienvenido de nuevo.'));
    }

    return redirect()->back()->withErrors([
      'nip' => __('Las credenciales proporcionadas no coinciden con nuestros registros.'),
    ])->onlyInput('correo');
  }

  public function logout(): RedirectResponse
  {
    $user = Auth::user();

    if ($user) {
      $this->singleSessionManager->clearSession($user);
    }

    Auth::logout();

    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('login.form')->with('status', __('Has cerrado sesión correctamente.'));
  }
  private function throttleKey(LoginRequest $request): string
  {
    return strtolower((string) $request->correo) . '|' . $request->ip();
  }
}
