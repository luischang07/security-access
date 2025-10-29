<?php

namespace App\Services;

use App\Domain\User\UserEntity;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Mailtrap\Config;
use Mailtrap\EmailHeader\CategoryHeader;
use Mailtrap\EmailHeader\CustomVariableHeader;
use Mailtrap\MailtrapClient;
use Mailtrap\Mime\MailtrapEmail;

class MailtrapSessionResetService
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

    // Mailtrap API
    try {
      $this->sendWithMailtrapAPI($user, $token, $email);
      Log::info("Email enviado exitosamente con Mailtrap API para: {$email}");
      return true;
    } catch (\Exception $e) {
      // Fallback a SMTP si API falla
      $result = $this->sendWithSMTP($user, $token, $email);
      return $result;
    }
  }

  private function sendWithMailtrapAPI(UserEntity $user, string $token, string $email): void
  {
    $mailtrapToken = config('services.mailtrap.token');

    if (!$mailtrapToken) {
      throw new \RuntimeException('Token de Mailtrap no configurado');
    }

    $mailtrap = new MailtrapClient(new Config($mailtrapToken));

    $resetUrl = route('session.reset', $token);

    // Crear email con Mailtrap
    $mailMessage = (new MailtrapEmail())
      ->from(config('mail.from.address'))
      ->to($email)
      ->subject('🔐 Solicitud de eliminación de sesión activa')
      ->html($this->getEmailTemplate($user, $resetUrl))
      ->text($this->getTextTemplate($user, $resetUrl))
      ->category('session-reset')
      ->customVariable('user_id', (string) $user->getId())
      ->customVariable('reset_type', 'session')
      ->customVariable('timestamp', now()->toISOString());

    // Enviar usando Mailtrap API
    $mailtrap->sending()->emails()->send($mailMessage);
  }

  private function sendWithSMTP(UserEntity $user, string $token, string $email): bool
  {
    // Fallback usando Laravel Mail tradicional
    Mail::send('emails.session-reset', [
      'user' => $user,
      'token' => $token,
      'url' => route('session.reset', $token),
    ], function ($message) use ($email) {
      $message->to($email)
        ->subject('🔐 Solicitud de eliminación de sesión activa');
    });

    return true;
  }

  private function getEmailTemplate(UserEntity $user, string $resetUrl): string
  {
    return view('emails.session-reset-mailtrap', [
      'user' => $user,
      'url' => $resetUrl,
      'expiresIn' => 60, // minutos
    ])->render();
  }

  private function getTextTemplate(UserEntity $user, string $resetUrl): string
  {
    return "Hola {$user->getName()},\n\n" .
      "Hemos detectado que intentaste iniciar sesión desde un nuevo dispositivo, " .
      "pero ya tienes una sesión activa en otro dispositivo.\n\n" .
      "Si deseas cerrar tu sesión actual para poder iniciar sesión desde el nuevo dispositivo, " .
      "visita el siguiente enlace:\n\n" .
      $resetUrl . "\n\n" .
      "Este enlace es válido por 1 hora.\n\n" .
      "Si no solicitaste esto, puedes ignorar este correo.\n\n" .
      "Security System";
  }

  public function resetSessionWithToken(string $token): array
  {
    $hashedToken = hash('sha256', $token);

    $resetToken = DB::table('session_reset_tokens')
      ->where('token', $hashedToken)
      ->where('created_at', '>', now()->subHours(1))
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

    $this->singleSessionManager->clearSession($user);

    DB::table('session_reset_tokens')
      ->where('email', $resetToken->email)
      ->delete();

    $this->sendConfirmationEmail($resetToken->email, $user);

    return [
      'success' => true,
      'message' => 'Sesión eliminada exitosamente. Ahora puedes iniciar sesión desde tu nuevo dispositivo.',
      'email' => $resetToken->email
    ];
  }

  private function sendConfirmationEmail(string $email, UserEntity $user): void
  {
    try {
      $mailtrapToken = config('services.mailtrap.token');

      if ($mailtrapToken) {
        $mailtrap = new MailtrapClient(new Config($mailtrapToken));

        $confirmationEmail = (new MailtrapEmail())
          ->from(config('mail.from.address'))
          ->to($email)
          ->subject('✅ Sesión eliminada exitosamente')
          ->html($this->getConfirmationTemplate($user))
          ->category('session-confirmation')
          ->customVariable('user_id', (string) $user->getId())
          ->customVariable('action', 'session_reset_confirmed');

        $mailtrap->sending()->emails()->send($confirmationEmail);
      }
    } catch (\Exception $e) {
      Log::warning('No se pudo enviar email de confirmación: ' . $e->getMessage());
    }
  }

  private function getConfirmationTemplate(UserEntity $user): string
  {
    return "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
            <h2 style='color: #28a745;'>✅ Sesión Eliminada</h2>
            <p>Hola <strong>{$user->getName()}</strong>,</p>
            <p>Tu sesión activa ha sido eliminada exitosamente.</p>
            <p>Ahora puedes iniciar sesión desde tu nuevo dispositivo.</p>
            <p style='color: #666; font-size: 12px;'>
                Si no solicitaste esta acción, por favor contacta al soporte técnico inmediatamente.
            </p>
        </div>";
  }

  public function cleanExpiredTokens(): int
  {
    return DB::table('session_reset_tokens')
      ->where('created_at', '<', now()->subHours(1))
      ->delete();
  }
}
