<?php

namespace App\Services;

use App\Domain\User\UserEntity;
use App\Http\Requests\RegisterRequest;
use App\Repositories\UserRepository;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegistrationService
{
  private UserRepository $userRepository;
  private SingleSessionManager $sessionManager;

  public function __construct(UserRepository $userRepository, SingleSessionManager $sessionManager)
  {
    $this->userRepository = $userRepository;
    $this->sessionManager = $sessionManager;
  }

  public function register(RegisterRequest $request): UserEntity
  {
    return DB::transaction(function () use ($request) {
      $userData = [
        'name' => $request->name,
        'email' => $request->email,
        'nip' => $request->nip, // Will be auto-hashed by the model
        'session_token' => null,
        'email_verified_at' => null,
      ];

      $user = $this->userRepository->create($userData);

      $sessionToken = Str::random(60);

      $this->userRepository->updateSessionDataWithLock(
        $user->getId(),
        $sessionToken,
        now()
      );

      $user->setSessionToken($sessionToken);
      $user->setLastLoginAt(now());

      $request->session()->put('user_id', $user->getId());
      $request->session()->put('session_token', $sessionToken);

      // Fire registered event (useful for any listeners like email verification)
      event(new Registered($user));

      return $user;
    });
  }
}
