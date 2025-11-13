<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\SessionResetController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PrescriptionController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'landing')->name('landing');

Route::middleware('guest')->group(function (): void {
  Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
  Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');

  Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
  Route::post('/register', [RegisterController::class, 'register'])
    ->name('register.attempt');

  // Rutas para reset de sesión
  Route::post('/session/reset/send', [SessionResetController::class, 'sendResetEmail'])->name('session.reset.send');
  Route::get('/session/reset/{token}', [SessionResetController::class, 'resetSession'])->name('session.reset');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'single.session'])->group(function (): void {
  // Patient Routes
  Route::prefix('patient')->name('patient.')->group(function () {
    Route::get('/dashboard', [PatientController::class, 'dashboard'])->name('dashboard');
    Route::get('/orders', [PatientController::class, 'orders'])->name('orders');
    Route::get('/orders/history', [PatientController::class, 'orderHistory'])->name('orders.history');
    Route::get('/profile', [PatientController::class, 'profile'])->name('profile');
    Route::get('/penalties', [PatientController::class, 'penalties'])->name('penalties');
    Route::get('/help', [PatientController::class, 'help'])->name('help');
  });

  // Prescription Routes
  Route::prefix('prescription')->name('prescription.')->group(function () {
    Route::get('/upload/step1', [PrescriptionController::class, 'uploadStep1'])->name('upload.step1');
    Route::get('/upload/step2', [PrescriptionController::class, 'uploadStep2'])->name('upload.step2');
    Route::get('/pharmacy-map', [PrescriptionController::class, 'pharmacyMap'])->name('pharmacy-map');
  });

  // Pharmacy Routes
  Route::prefix('pharmacy')->name('pharmacy.')->group(function () {
    Route::get('/dashboard', [PharmacyController::class, 'dashboard'])->name('dashboard');
    Route::get('/orders', [PharmacyController::class, 'orders'])->name('orders');
    Route::get('/inventory', [PharmacyController::class, 'inventory'])->name('inventory');
    Route::get('/reports', [PharmacyController::class, 'reports'])->name('reports');
  });

  // Admin Routes
  Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/pharmacies', [AdminController::class, 'pharmacies'])->name('pharmacies');
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::get('/penalties', [AdminController::class, 'penalties'])->name('penalties');
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
  });

  // Settings route (common for all user types)
  Route::view('/settings', 'settings')->name('settings');
});
