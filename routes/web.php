<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\SessionResetController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'landing')->name('landing');

Route::middleware('guest')->group(function (): void {
  Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
  Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');

  Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
  Route::post('/register', [RegisterController::class, 'register'])
    ->middleware('throttle:registration')
    ->name('register.attempt');

  // Rutas para reset de sesión
  Route::post('/session/reset/send', [SessionResetController::class, 'sendResetEmail'])->name('session.reset.send');
  Route::get('/session/reset/{token}', [SessionResetController::class, 'resetSession'])->name('session.reset');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'single.session'])->group(function (): void {
  Route::view('/dashboard', 'dashboard')->name('dashboard');
});
