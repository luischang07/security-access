<?php

namespace App\Services;

use App\Domain\User\UserEntity;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SessionResetService
{
  public function __construct(
    private readonly UserRepository $userRepository,
    private readonly SingleSessionManager $singleSessionManager
  ) {}

  public function sendSessionResetEmail(string $email): bool
  {
    $user = $this->userRepository->findByEmail($email);

    if (!$user || !$user->hasActiveSession()) {
      return false;
    }

    $token = Str::random(64);

    // Guardar el token en la base de datos
    DB::table('session_reset_tokens')->updateOrInsert(
      ['email' => $email],
      [
        'token' => hash('sha256', $token),
        'created_at' => now(),
      ]
    );

    // Enviar el correo
    Mail::send('emails.session-reset', [
      'user' => $user,
      'token' => $token,
      'url' => route('session.reset', $token),
    ], function ($message) use ($email) {
      $message->to($email)
        ->subject('Solicitud de eliminación de sesión activa');
    });

    return true;
  }

  public function resetSessionWithToken(string $token): array
  {
    $hashedToken = hash('sha256', $token);

    $resetToken = DB::table('session_reset_tokens')
      ->where('token', $hashedToken)
      ->where('created_at', '>', now()->subHours(1)) // Válido por 1 hora
      ->first();

    if (!$resetToken) {
      return [
        'success' => false,
        'message' => 'Token inválido o expirado.'
      ];
    }

    $user = $this->userRepository->findByEmail($resetToken->email);

    if (!$user) {
      return [
        'success' => false,
        'message' => 'Usuario no encontrado.'
      ];
    }

    // Eliminar la sesión activa
    $this->singleSessionManager->clearSession($user);

    // Eliminar el token usado
    DB::table('session_reset_tokens')
      ->where('email', $resetToken->email)
      ->delete();

    return [
      'success' => true,
      'message' => 'Sesión eliminada exitosamente. Ahora puedes iniciar sesión desde tu nuevo dispositivo.',
      'email' => $resetToken->email
    ];
  }

  public function cleanExpiredTokens(): int
  {
    return DB::table('session_reset_tokens')
      ->where('created_at', '<', now()->subHours(1))
      ->delete();
  }
}
