<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\SessionResetController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\OrdenesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GestionPedidoController;
use App\Http\Controllers\CancelacionController;

Route::view('/', 'landing')->name('landing');
Route::get('lang/{locale}', [App\Http\Controllers\LanguageController::class, 'switch'])->name('lang.switch');

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
    Route::get('/orders', [GestionPedidoController::class, 'getPedidos'])->name('orders');
    Route::get('/orders/{folio}', [GestionPedidoController::class, 'getPedido'])->name('orders.show');
    Route::get('/orders/history', [PatientController::class, 'orderHistory'])->name('orders.history');
    Route::get('/profile', [PatientController::class, 'profile'])->name('profile');
    Route::put('/profile', [PatientController::class, 'updateProfile'])->name('profile.update');
    Route::get('/penalties', [PatientController::class, 'penalties'])->name('penalties');
    Route::get('/help', [PatientController::class, 'help'])->name('help');
  });

  // Prescription Routes
  Route::prefix('prescription')->name('prescription.')->group(function () {

    Route::get('/upload/step1', [GestionPedidoController::class, 'nuevoPedido'])->name('upload.step1');
    Route::post('/upload/step1', [GestionPedidoController::class, 'confirmarCaptura'])->name('upload.step1.store');
    Route::get('/upload/step2', [GestionPedidoController::class, 'confirmarCaptura'])->name('upload.step2');
    Route::post('/upload/step2', [GestionPedidoController::class, 'confirmarPedido'])->name('upload.step2.store');
    Route::get('/pharmacy-map', [PrescriptionController::class, 'pharmacyMap'])->name('pharmacy-map');

    //ruta para procesar la sucursal
    Route::post('/sucursal/procesar', [GestionPedidoController::class, 'seleccionarSucursal'])->name('sucursal.procesar');
    Route::get('/medications/search', [GestionPedidoController::class, 'buscarMedicamentos'])->name('medications.search');
    Route::post('/medications/add', [GestionPedidoController::class, 'agregarMedicamento'])->name('medications.add');
    Route::post('/medications/remove', [GestionPedidoController::class, 'eliminarMedicamento'])->name('medications.remove');

    // AJAX: obtener sucursales por cadena
    Route::get('/sucursales/{cadena_id}', [PrescriptionController::class, 'sucursalesPorCadena'])->name('sucursales.by_cadena');

    // AJAX: obtener datos del mapa de farmacias
    Route::get('/pharmacies-data', [PrescriptionController::class, 'getPharmaciesData'])->name('pharmacies.data');
  });

  // Pharmacy Routes
  Route::prefix('pharmacy')->name('pharmacy.')->group(function () {
    Route::get('/dashboard', [PharmacyController::class, 'dashboard'])->name('dashboard');
    Route::get('/orders', [PharmacyController::class, 'orders'])->name('orders');
    Route::post('/orders/cancel/{folio}', [CancelacionController::class, 'cancelarPorFolio'])->name('cancelarOrdenPorFolio');
    Route::post('/orders/mark-surtido/{folio}', [PharmacyController::class, 'marcarComoSurtido'])->name('orders.markSurtido');
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
    Route::get('/chains', [AdminController::class, 'chains'])->name('chains');
  });

  // Settings route (common for all user types)
  Route::view('/settings', 'settings')->name('settings');
});

Route::middleware('api')->prefix('api')->group(function () {
    Route::get('/pedido/buscar', [OrdenesController::class, 'buscarPorFolio']);
});
