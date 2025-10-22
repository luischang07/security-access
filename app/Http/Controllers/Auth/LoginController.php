<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\AuthenticationService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller
{
  public function __construct(
    private readonly AuthenticationService $authenticationService
  ) {}

  public function showLoginForm(): View
  {
    return view('auth.login');
  }

  public function login(LoginRequest $request): RedirectResponse
  {
    return $this->authenticationService->attemptLogin($request);
  }

  public function logout(): RedirectResponse
  {
    return $this->authenticationService->logout();
  }
}
