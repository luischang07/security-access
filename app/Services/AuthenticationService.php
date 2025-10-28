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
use Illuminate\Support\Str;

class AuthenticationService
{
  public function __construct(
    private readonly UserRepository $userRepository,
    private readonly SingleSessionManager $singleSessionManager,
    private readonly LoginThrottleService $loginThrottleService
  ) {}

  public function attemptLogin(LoginRequest $request): RedirectResponse
  {
    return DB::transaction(function () use ($request) {
      $userModel = $this->userRepository->findByEmailWithLock($request->correo);

      if (!$userModel) {
        return $this->handleFailedLogin($request);
      }

      $user = new UserEntity($userModel);

      // Verificar si la cuenta está bloqueada o si puede intentar login
      if (!$this->loginThrottleService->canAttemptLogin($user)) {
        $remainingTime = $this->loginThrottleService->getTimeUntilUnlock($user);
        $minutes = ceil($remainingTime / 60);

        return redirect()->back()->withErrors([
          'nip' => __('Cuenta bloqueada temporalmente. Intenta nuevamente en :minutes minuto(s).', [
            'minutes' => $minutes
          ]),
        ])->onlyInput($request->only('correo'));
      }

      if (Hash::check($request->nip, $userModel->nip)) {
        return $this->handleSuccessfulLogin($request, $userModel, $user);
      }

      return $this->handleFailedLogin($request, $user);
    });
  }

  private function handleSuccessfulLogin(LoginRequest $request, $userModel, UserEntity $user): RedirectResponse
  {
    // Resetear intentos de login en caso de éxito
    $this->loginThrottleService->recordSuccessfulLogin($user);

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

  private function handleFailedLogin(LoginRequest $request, ?UserEntity $user = null): RedirectResponse
  {
    // Si tenemos el usuario, registrar el intento fallido
    if ($user) {
      $this->loginThrottleService->recordFailedAttempt($user);
      $remainingAttempts = $this->loginThrottleService->getRemainingAttempts($user);

      if ($remainingAttempts > 0) {
        return redirect()->back()->withErrors([
          'nip' => __('Las credenciales proporcionadas no coinciden con nuestros registros. Te quedan :attempts intento(s).', [
            'attempts' => $remainingAttempts
          ]),
        ])->onlyInput($request->only('correo'));
      }
    }

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
}
