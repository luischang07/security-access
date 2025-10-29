<?php

namespace App\Services;

use App\Domain\User\UserEntity;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;

class SingleSessionManager
{
  // Duración de sesión: 24 horas por defecto, 30 días si se marca "recordarme"
  private const DEFAULT_SESSION_DURATION_HOURS = 24;
  private const REMEMBER_SESSION_DURATION_DAYS = 30;

  public function __construct(
    private readonly UserRepository $userRepository
  ) {}

  public function hasActiveSession(UserEntity $user): bool
  {
    if ($user->isSessionExpired()) {
      $this->clearSession($user);
      return false;
    }

    return $user->hasActiveSession();
  }

  public function registerSession(UserEntity $user, string $token, bool $remember = false): void
  {
    $expiresAt = $remember
      ? now()->addDays(self::REMEMBER_SESSION_DURATION_DAYS)
      : now()->addHours(self::DEFAULT_SESSION_DURATION_HOURS);

    DB::transaction(function () use ($user, $token, $expiresAt) {
      $this->userRepository->updateSessionDataWithLock(
        $user->getId(),
        $token,
        now(),
        $expiresAt
      );
    });
  }

  public function clearSession(UserEntity $user): void
  {
    $this->userRepository->clearSession($user->getId());
  }

  public function validateSession(UserEntity $user, string $sessionToken): bool
  {
    if ($user->isSessionExpired()) {
      $this->clearSession($user);
      return false;
    }

    $dbToken = $user->getSessionToken();
    return $dbToken && $sessionToken && $dbToken === $sessionToken;
  }

  public function getSessionExpirationInfo(UserEntity $user): ?array
  {
    if (!$user->hasActiveSession()) {
      return null;
    }

    $expiresAt = $user->getSessionExpiresAt();
    if (!$expiresAt) {
      return null;
    }

    return [
      'expires_at' => $expiresAt,
      'expires_in_hours' => max(0, $expiresAt->diffInHours(now())),
      'expires_in_minutes' => max(0, $expiresAt->diffInMinutes(now())),
      'is_expired' => $expiresAt->isPast(),
    ];
  }
}
