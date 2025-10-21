<?php

namespace App\Services;

use App\Models\User;

class SingleSessionManager
{
  public function registerSession(User $user, string $token): void
  {
    $user->forceFill([
      'session_token' => $token,
      'last_login_at' => now(),
    ])->save();
  }

  public function clearSession(User $user): void
  {
    $user->forceFill([
      'session_token' => null,
    ])->save();
  }
}
