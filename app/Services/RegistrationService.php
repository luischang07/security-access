<?php

namespace App\Services;

use App\Domain\User\UserEntity;
use App\Http\Requests\RegisterRequest;
use App\Repositories\UserRepository;
use App\Repositories\PacienteRepository;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class RegistrationService
{
  private UserRepository $userRepository;
  private SingleSessionManager $sessionManager;
  private PacienteRepository $pacienteRepository;

  public function __construct(
    UserRepository $userRepository,
    SingleSessionManager $sessionManager,
    PacienteRepository $pacienteRepository
  ) {
    $this->userRepository = $userRepository;
    $this->sessionManager = $sessionManager;
    $this->pacienteRepository = $pacienteRepository;
  }

  public function register(RegisterRequest $request): UserEntity
  {
    return DB::transaction(function () use ($request) {
      $userData = [
        'nombre' => $request->nombre,
        'apellido' => $request->apellido,
        'correo' => $request->email,
        'password' => $request->password,
        'session_token' => null,
        'email_verified_at' => null,
      ];

      $user = $this->userRepository->create($userData);

      // Create Paciente record for the new user
      $this->pacienteRepository->create($user->getId());

      $sessionToken = Str::random(60);

      $this->userRepository->updateSessionDataWithLock(
        $user->getId(),
        $sessionToken,
        now()
      );

      $user->setSessionToken($sessionToken);
      $user->setUltimoLogin(Carbon::now());

      Auth::loginUsingId($user->getId());

      $request->session()->put('user_id', $user->getId());
      $request->session()->put('session_token', $sessionToken);
      $request->session()->save();

      event(new Registered(Auth::user()));

      return $user;
    });
  }
}
