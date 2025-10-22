<?php

namespace App\Services;

use App\Domain\User\UserEntity;
use App\Repositories\UserRepository;

class SingleSessionManager
{
  public function __construct(
    private readonly UserRepository $userRepository
  ) {}

  public function registerSession(UserEntity $user, string $token): void
  {
    $this->userRepository->updateSessionData(
      $user->getId(),
      $token,
      now()
    );
  }

  public function clearSession(UserEntity $user): void
  {
    $this->userRepository->clearSession($user->getId());
  }
}
