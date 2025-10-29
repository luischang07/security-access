<?php

namespace App\Http\Controllers;

use App\Services\MailtrapSessionResetService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class SessionResetController extends Controller
{
  public function __construct(
    private readonly MailtrapSessionResetService $sessionResetService
  ) {}

  public function sendResetEmail(Request $request): RedirectResponse
  {

    $request->validate([
      'email' => 'required|email|exists:users,email'
    ]);

    $sent = $this->sessionResetService->sendSessionResetEmail($request->email);

    if ($sent) {
      return redirect()->back()->with(
        'status',
        'Se ha enviado un correo electrónico con instrucciones para eliminar tu sesión activa.'
      );
    }

    return redirect()->back()->withErrors([
      'email' => 'No se encontró una sesión activa para este correo electrónico.'
    ]);
  }

  public function resetSession(string $token): RedirectResponse
  {
    $result = $this->sessionResetService->resetSessionWithToken($token);

    if ($result['success']) {
      return redirect()->route('login')
        ->with('status', $result['message'])
        ->with('reset_email', $result['email']);
    }

    return redirect()->route('login')
      ->withErrors(['session_reset' => $result['message']]);
  }
}
