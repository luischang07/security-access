<?php

namespace App\Services;

use App\Domain\User\UserEntity;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;

class LoginThrottleService
{
  public function __construct(
    private readonly UserRepository $userRepository
  ) {}

  public function canAttemptLogin(UserEntity $user): bool
  {
    // Si necesita resetear intentos, lo hacemos
    if ($user->shouldResetLoginAttempts()) {
      $this->resetLoginAttempts($user);
      return true;
    }

    return $user->canAttemptLogin();
  }

  public function recordFailedAttempt(UserEntity $user): void
  {
    $this->userRepository->incrementLoginAttempts($user->getId());
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

    return $user->getLockedUntil()->diffInSeconds(now());
  }

  public function getRemainingAttempts(UserEntity $user): int
  {
    if ($user->shouldResetLoginAttempts()) {
      return 4;
    }

    return max(0, 4 - $user->getLoginAttempts());
  }
}
