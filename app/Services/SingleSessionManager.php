<?php

namespace App\Services;

use App\Domain\User\UserEntity;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;

class SingleSessionManager
{
  public function __construct(
    private readonly UserRepository $userRepository
  ) {}

  public function registerSession(UserEntity $user, string $token): void
  {
    DB::transaction(function () use ($user, $token) {
      $this->userRepository->updateSessionDataWithLock(
        $user->getId(),
        $token,
        now()
      );
    });
  }

  public function clearSession(UserEntity $user): void
  {
    $this->userRepository->clearSession($user->getId());
  }

  public function validateSession(UserEntity $user, string $sessionToken): bool
  {
    $dbToken = $user->getSessionToken();
    return $dbToken && $sessionToken && $dbToken === $sessionToken;
  }
}
