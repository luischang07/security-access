<?php

namespace App\Services;

use App\Domain\User\UserEntity;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;

class LoginThrottleService
{
  private const MAX_LOGIN_ATTEMPTS = 4;
  private const LOCKOUT_DURATION_MINUTES = 1;

  public function __construct(
    private readonly UserRepository $userRepository
  ) {}

  public function canAttemptLogin(UserEntity $user): bool
  {
    if ($user->shouldResetLoginAttempts()) {
      $user->setLoginAttempts(0);
      $user->setLoginAttemptsResetAt(null);
      $user->setLockedUntil(null);
      return true;
    }

    return $user->canAttemptLogin();
  }

  public function canAttemptLoginInTransaction(UserEntity $user): bool
  {
    if ($user->shouldResetLoginAttempts()) {
      $this->resetLoginAttemptsInTransaction($user);
      return true;
    }

    return $user->canAttemptLogin();
  }

  public function resetLoginAttemptsInTransaction(UserEntity $user): bool
  {
    $user->setLoginAttempts(0);
    $user->setLoginAttemptsResetAt(null);
    $user->setLockedUntil(null);

    return $this->userRepository->resetLoginAttempts($user->getId());
  }

  public function recordFailedAttemptInTransaction(UserEntity $user): array
  {
    $attempts = $user->getLoginAttempts() + 1;
    $user->setLoginAttempts($attempts);

    if ($attempts >= self::MAX_LOGIN_ATTEMPTS) {
      $lockedUntil = now()->addMinutes(self::LOCKOUT_DURATION_MINUTES);
      $resetAt = now()->addMinutes(self::LOCKOUT_DURATION_MINUTES);

      $user->setLockedUntil($lockedUntil);
      $user->setLoginAttemptsResetAt($resetAt);
    }

    $success = $this->userRepository->updateLoginAttempts(
      $user->getId(),
      $attempts,
      $user->getLoginAttemptsResetAt(),
      $user->getLockedUntil()
    );

    return [
      'success' => $success,
      'user' => $user
    ];
  }

  public function recordSuccessfulLogin(UserEntity $user): void
  {
    if ($user->getLoginAttempts() > 0) {
      $this->resetLoginAttempts($user);
    }
  }

  public function resetLoginAttempts(UserEntity $user): void
  {
    $this->userRepository->resetLoginAttempts($user->getId());
  }

  public function getTimeUntilUnlock(UserEntity $user): ?int
  {
    if (!$user->isLocked()) {
      return null;
    }

    $lockedUntil = $user->getLockedUntil();
    $now = now();

    if ($now->greaterThanOrEqualTo($lockedUntil)) {
      return 0;
    }

    $seconds = $now->diffInSeconds($lockedUntil, false);

    return max(0, (int) $seconds);
  }

  public function getFormattedTimeUntilUnlock(UserEntity $user): ?string
  {
    $seconds = $this->getTimeUntilUnlock($user);

    if ($seconds === null) {
      return null;
    }

    if ($seconds <= 0) {
      return '0 segundos';
    }

    $minutes = floor($seconds / 60);
    $remainingSeconds = $seconds % 60;

    if ($minutes > 0) {
      return $minutes . ' minuto(s)' . ($remainingSeconds > 0 ? ' y ' . $remainingSeconds . ' segundo(s)' : '');
    }

    return $remainingSeconds . ' segundo(s)';
  }

  public function getRemainingAttempts(UserEntity $user): int
  {
    if ($user->shouldResetLoginAttempts()) {
      return self::MAX_LOGIN_ATTEMPTS;
    }

    return max(0, self::MAX_LOGIN_ATTEMPTS - $user->getLoginAttempts());
  }

  public function getRemainingSecondsForJs(UserEntity $user): int
  {
    $seconds = $this->getTimeUntilUnlock($user);
    return $seconds ?: 0;
  }
}
