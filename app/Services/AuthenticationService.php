<?php

namespace App\Services;

use App\Domain\User\UserEntity;
use App\Http\Requests\LoginRequest;
use App\Repositories\UserRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthenticationService
{
  public function __construct(
    private readonly UserRepository $userRepository,
    private readonly SingleSessionManager $singleSessionManager
  ) {}

  public function attemptLogin(LoginRequest $request): RedirectResponse
  {
    return DB::transaction(function () use ($request) {
      $userModel = $this->userRepository->findByEmailWithLock($request->correo);

      if ($userModel && Hash::check($request->nip, $userModel->nip)) {
        return $this->handleSuccessfulLogin($request, $userModel);
      }

      return $this->handleFailedLogin($request);
    });
  }

  private function handleSuccessfulLogin(LoginRequest $request, $userModel): RedirectResponse
  {
    $user = new UserEntity($userModel);

    RateLimiter::clear($this->getThrottleKey($request));

    $sessionToken = Str::uuid()->toString();

    $remember = $request->has('remember');

    $request->session()->put('remember_me', $remember);

    if ($remember) {
      $rememberToken = Str::random(60);
      $userModel->remember_token = $rememberToken;
      $userModel->save();
    } else {
      $userModel->remember_token = null;
      $userModel->save();
    }

    $this->singleSessionManager->registerSession($user, $sessionToken);

    $request->session()->put('session_token', $sessionToken);

    Auth::loginUsingId($user->getId(), $remember);

    $request->session()->regenerate();

    return redirect()->route('landing')->with('status', __('Bienvenido de nuevo.'));
  }

  private function handleFailedLogin(LoginRequest $request): RedirectResponse
  {
    return redirect()->back()->withErrors([
      'nip' => __('Las credenciales proporcionadas no coinciden con nuestros registros.'),
    ])->onlyInput($request->only('correo'));
  }

  public function logout(): RedirectResponse
  {
    $authUser = Auth::user();

    if ($authUser) {
      $user = $this->userRepository->findById($authUser->id);
      if ($user) {
        $this->singleSessionManager->clearSession($user);
      }
    }

    Auth::logout();

    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('login')->with('status', __('Has cerrado sesión correctamente.'));
  }

  private function getThrottleKey(LoginRequest $request): string
  {
    return strtolower((string) $request->correo) . '|' . $request->ip();
  }
}
