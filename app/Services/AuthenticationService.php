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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AuthenticationService
{
  public function __construct(
    private readonly UserRepository $userRepository,
    private readonly SingleSessionManager $singleSessionManager,
    private readonly LoginThrottleService $loginThrottleService
  ) {
  }

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

      if (Hash::check($request->password, $userModel->password)) {
        return $this->handleSuccessfulLogin($request, $userModel, $user);
      }

      return $this->handleFailedLogin($request, $user);
    });
  }

  private function handleSuccessfulLogin(LoginRequest $request, $userModel, UserEntity $user): RedirectResponse
  {
    $this->loginThrottleService->recordSuccessfulLogin($user);

    // ✅ Log login exitoso
    Log::channel('security')->info('Successful login', [
      'user_id' => $user->getId(),
      'correo' => $user->getCorreo(),
      'ip' => $request->ip(),
      'user_agent' => $request->userAgent(),
      'timestamp' => now()->toDateTimeString(),
    ]);

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

    Auth::loginUsingId($user->getId(), $remember);

    $request->session()->regenerate();

    // Guardar el session_token DESPUÉS de regenerar la sesión
    $request->session()->put('session_token', $sessionToken);

    // ✅ RBAC: Redirigir según el rol del usuario
    $role = $userModel->getRole();

    return match ($role) {
      'admin' => redirect()->route('admin.dashboard')->with('status', __('Bienvenido de nuevo, Administrador.')),
      'pharmacy' => redirect()->route('pharmacy.dashboard')->with('status', __('Bienvenido de nuevo.')),
      'patient' => redirect()->route('patient.dashboard')->with('status', __('Bienvenido de nuevo.')),
      default => redirect()->route('landing')->with('status', __('Bienvenido de nuevo.')),
    };
  }

  private function handleFailedLogin(LoginRequest $request, ?UserEntity $user = null): RedirectResponse
  {
    // ✅ Log login fallido
    Log::channel('security')->warning('Failed login attempt', [
      'email' => $request->correo,
      'ip' => $request->ip(),
      'user_agent' => $request->userAgent(),
      'user_exists' => $user !== null,
      'timestamp' => now()->toDateTimeString(),
    ]);

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
            'password' => __('Las credenciales proporcionadas no coinciden con nuestros registros. Te quedan :attempts intento(s).', [
              'attempts' => $remainingAttempts
            ]),
          ])->onlyInput($request->only('correo'));
        }
      }
    }

    return redirect()->back()->withErrors([
      'password' => __('Las credenciales proporcionadas no coinciden con nuestros registros.'),
    ])->onlyInput($request->only('correo'));
  }

  public function logout(): RedirectResponse
  {
    $authUser = Auth::user();

    // ✅ Log logout
    if ($authUser) {
      Log::channel('security')->info('User logout', [
        'user_id' => $authUser->user_id,
        'email' => $authUser->correo,
        'ip' => request()->ip(),
        'timestamp' => now()->toDateTimeString(),
      ]);
    }

    if ($authUser) {
      $user = $this->userRepository->findById($authUser->user_id);
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
    // ✅ Log cuenta bloqueada
    Log::channel('security')->warning('Account locked due to failed attempts', [
      'user_id' => $user->getId(),
      'email' => $request->correo,
      'ip' => $request->ip(),
      'attempts' => $user->getLoginAttempts(),
      'timestamp' => now()->toDateTimeString(),
    ]);

    $formattedTime = $this->loginThrottleService->getFormattedTimeUntilUnlock($user);
    $remainingSeconds = $this->loginThrottleService->getRemainingSecondsForJs($user);

    return redirect()->back()->withErrors([
      'password' => __('Cuenta bloqueada temporalmente. Intenta nuevamente en :time.', [
        'time' => $formattedTime
      ]),
    ])->withInput($request->only('correo'))
      ->with('lockout_seconds', $remainingSeconds);
  }

  private function handleActiveSessionError(LoginRequest $request): RedirectResponse
  {
    return redirect()->back()->withErrors([
      'correo' => __('Ya existe una sesión activa para esta cuenta. Puedes solicitar que se elimine enviando un correo a tu dirección de correo.'),
    ])->onlyInput($request->only('correo'))
      ->with('show_session_reset', true);
  }
}
