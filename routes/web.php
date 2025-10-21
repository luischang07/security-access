<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'landing')->name('landing');

Route::middleware('guest')->group(function (): void {
  Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');
  Route::post('/login', [LoginController::class, 'login'])
    ->middleware('throttle:login')
    ->name('login.attempt');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'single.session'])->group(function (): void {
  Route::view('/dashboard', 'dashboard')->name('dashboard');
});
