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

      if (!$this->loginThrottleService->canAttemptLoginInTransaction($user)) {
        return $this->handleAccountLockout($request, $user);
      }

      if ($this->singleSessionManager->hasActiveSession($user)) {
        return $this->handleActiveSessionError($request);
      }

      if (Hash::check($request->nip, $userModel->nip)) {
        return $this->handleSuccessfulLogin($request, $userModel, $user);
      }

      return $this->handleFailedLogin($request, $user);
    });
  }

  private function handleSuccessfulLogin(LoginRequest $request, $userModel, UserEntity $user): RedirectResponse
  {
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

    $this->singleSessionManager->registerSession($user, $sessionToken, $remember);

    $request->session()->put('session_token', $sessionToken);

    Auth::loginUsingId($user->getId(), $remember);

    $request->session()->regenerate();

    return redirect()->route('landing')->with('status', __('Bienvenido de nuevo.'));
  }

  private function handleFailedLogin(LoginRequest $request, ?UserEntity $user = null): RedirectResponse
  {
    if ($user) {
      $result = $this->loginThrottleService->recordFailedAttemptInTransaction($user);

      if ($result['success']) {
        $updatedUser = $result['user'];

        if ($updatedUser->getLoginAttempts() >= 4) {
          return $this->handleAccountLockout($request, $updatedUser);
        }

        $remainingAttempts = $this->loginThrottleService->getRemainingAttempts($updatedUser);

        if ($remainingAttempts > 0) {
          return redirect()->back()->withErrors([
            'nip' => __('Las credenciales proporcionadas no coinciden con nuestros registros. Te quedan :attempts intento(s).', [
              'attempts' => $remainingAttempts
            ]),
          ])->onlyInput($request->only('correo'));
        }
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

  private function handleAccountLockout(LoginRequest $request, UserEntity $user): RedirectResponse
  {

    $formattedTime = $this->loginThrottleService->getFormattedTimeUntilUnlock($user);
    $remainingSeconds = $this->loginThrottleService->getRemainingSecondsForJs($user);

    return redirect()->back()->withErrors([
      'nip' => __('Cuenta bloqueada temporalmente. Intenta nuevamente en :time.', [
        'time' => $formattedTime
      ]),
    ])->withInput($request->only('correo'))
      ->with('lockout_seconds', $remainingSeconds);
  }

  private function handleActiveSessionError(LoginRequest $request): RedirectResponse
  {
    return redirect()->back()->withErrors([
      'correo' => __('Ya existe una sesión activa para esta cuenta. Puedes solicitar que se elimine enviando un correo a tu dirección de email.'),
    ])->onlyInput($request->only('correo'))
      ->with('show_session_reset', true);
  }
}
