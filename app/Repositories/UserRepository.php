<?php

namespace App\Repositories;

use App\Domain\User\UserEntity;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserRepository
{
  public function findByEmailWithLock(string $email): ?User
  {
    return User::where('email', $email)->lockForUpdate()->first();
  }

  public function findByEmail(string $email): ?UserEntity
  {
    $userModel = User::where('email', $email)->first();
    return $userModel ? new UserEntity($userModel) : null;
  }

  public function findById(int $id): ?UserEntity
  {
    $userModel = User::find($id);
    return $userModel ? new UserEntity($userModel) : null;
  }

  public function findByIdWithLock(int $id): ?UserEntity
  {
    $userModel = DB::transaction(function () use ($id) {
      return User::where('id', $id)->lockForUpdate()->first();
    });

    return $userModel ? new UserEntity($userModel) : null;
  }

  public function create(array $data): UserEntity
  {
    $userModel = User::create($data);
    return new UserEntity($userModel);
  }

  public function updateSessionData(int $userId, ?string $sessionToken, ?\DateTime $lastLoginAt): bool
  {
    return User::where('id', $userId)
      ->update([
        'session_token' => $sessionToken,
        'last_login_at' => $lastLoginAt,
      ]) > 0;
  }

  public function updateSessionDataWithLock(int $userId, ?string $sessionToken, ?\DateTime $lastLoginAt): bool
  {
    return DB::transaction(function () use ($userId, $sessionToken, $lastLoginAt) {
      return User::where('id', $userId)
        ->lockForUpdate()
        ->update([
          'session_token' => $sessionToken,
          'last_login_at' => $lastLoginAt,
        ]) > 0;
    });
  }

  public function clearSession(int $userId): bool
  {
    return DB::transaction(function () use ($userId) {
      return User::where('id', $userId)
        ->lockForUpdate()
        ->update(['session_token' => null]) > 0;
    });
  }
}
