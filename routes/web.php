<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'landing')->name('landing');

Route::middleware('guest')->group(function (): void {
  Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
  Route::post('/login', [LoginController::class, 'login'])
    ->middleware('throttle:login')
    ->name('login.attempt');

  Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
  Route::post('/register', [RegisterController::class, 'register'])
    ->middleware('throttle:registration')
    ->name('register.attempt');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'single.session'])->group(function (): void {
  Route::view('/dashboard', 'dashboard')->name('dashboard');
});
