<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Services\RegistrationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RegisterController extends Controller
{
  private RegistrationService $registrationService;

  public function __construct(RegistrationService $registrationService)
  {
    $this->registrationService = $registrationService;
  }

  public function showRegisterForm(): View
  {
    return view('auth.register');
  }

  public function register(RegisterRequest $request): RedirectResponse
  {
    $this->registrationService->register($request);

    return redirect()->route('dashboard')->with('success', '¡Bienvenido! Tu cuenta ha sido creada exitosamente.');
  }
}
