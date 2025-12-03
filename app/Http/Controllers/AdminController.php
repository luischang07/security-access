<?php

namespace App\Http\Controllers;

use App\Services\AdminDashboardService;
use App\Services\AdminUserService;
use App\Services\AdminPharmacyService;
use App\Services\AdminOrderService;
use App\Services\AdminPenaltyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @method void middleware(\Closure|string $middleware)
 */
class AdminController extends Controller
{
  protected AdminDashboardService $dashboardService;
  protected AdminUserService $userService;
  protected AdminPharmacyService $pharmacyService;
  protected AdminOrderService $orderService;
  protected AdminPenaltyService $penaltyService;

  /**
   * Verificar que el usuario tenga rol de administrador
   */
  public function __construct(
    AdminDashboardService $dashboardService,
    AdminUserService $userService,
    AdminPharmacyService $pharmacyService,
    AdminOrderService $orderService,
    AdminPenaltyService $penaltyService
  ) {
    $this->dashboardService = $dashboardService;
    $this->userService = $userService;
    $this->pharmacyService = $pharmacyService;
    $this->orderService = $orderService;
    $this->penaltyService = $penaltyService;

    $this->middleware(function ($request, $next) {
      /** @var \App\Models\User|null $user */
      $user = Auth::user();

      if (!Auth::check() || !$user || !$user->isAdmin()) {
        abort(403, 'No tienes permisos de administrador para acceder a esta sección.');
      }
      return $next($request);
    });
  }

  /**
   * Show the admin dashboard
   */
  public function dashboard(Request $request)
  {
    $stats = $this->dashboardService->getDashboardStats();
    $registrations = $this->dashboardService->getNewRegistrations();

    if ($request->wantsJson()) {
      return response()->json([
        'html' => view('admin.partials.dashboard-content', compact('stats', 'registrations'))->render(),
        'title' => __('admin.dashboard.title')
      ]);
    }

    return view('admin.dashboard', compact('stats', 'registrations'));
  }

  /**
   * Show the user management page
   */
  public function users(Request $request)
  {
    $filters = [
      'search' => $request->get('search'),
      'role' => $request->get('role'),
      'status' => $request->get('status'),
      'order_by' => $request->get('order_by', 'created_at'),
      'order_direction' => $request->get('order_direction', 'desc'),
    ];

    $perPage = $request->get('per_page', 10);
    $data = $this->userService->getUsersList($filters, $perPage);

    if ($request->wantsJson()) {
      return response()->json([
        'html' => view('admin.partials.users-content', [
          'users' => $data['users'],
          'pagination' => $data['pagination'],
          'stats' => $data['stats'],
          'filters' => $filters,
        ])->render(),
        'title' => __('admin.users.title')
      ]);
    }

    return view('admin.users', [
      'users' => $data['users'],
      'pagination' => $data['pagination'],
      'stats' => $data['stats'],
      'filters' => $filters,
    ]);
  }

  /**
   * Show the pharmacy chains management page
   */
  public function chains(Request $request)
  {
    if ($request->wantsJson()) {
      return response()->json([
        'html' => view('admin.partials.chains-content')->render(),
        'title' => __('admin.chains.title')
      ]);
    }

    return view('admin.chains');
  }

  /**
   * Show the pharmacy management page
   */
  public function pharmacies(Request $request)
  {
    $filters = [
      'search' => $request->get('search'),
      'chain' => $request->get('chain'),
      'status' => $request->get('status'),
    ];

    $perPage = $request->get('per_page', 12);
    $data = $this->pharmacyService->getPharmaciesList($filters, $perPage);

    if ($request->wantsJson()) {
      return response()->json([
        'html' => view('admin.partials.pharmacies-content', [
          'pharmacies' => $data['pharmacies'],
          'stats' => $data['stats'],
          'chains' => $data['chains'],
          'filters' => $filters,
        ])->render(),
        'title' => __('admin.pharmacies.title')
      ]);
    }

    return view('admin.pharmacies', [
      'pharmacies' => $data['pharmacies'],
      'stats' => $data['stats'],
      'chains' => $data['chains'],
      'filters' => $filters,
    ]);
  }

  /**
   * Show the orders overview
   */
  public function orders(Request $request)
  {
    $filters = [
      'search' => $request->get('search'),
      'status' => $request->get('status'),
      'pharmacy' => $request->get('pharmacy'),
      'date' => $request->get('date'),
      'per_page' => $request->get('per_page', 10),
    ];

    $data = $this->orderService->getOrdersList($filters, $filters['per_page']);

    if ($request->wantsJson()) {
      return response()->json([
        'html' => view('admin.partials.orders-content', [
          'orders' => $data['orders'],
          'stats' => $data['stats'],
          'chains' => $data['chains'],
          'filters' => $filters,
        ])->render(),
        'title' => __('admin.orders.title')
      ]);
    }

    return view('admin.orders', [
      'orders' => $data['orders'],
      'stats' => $data['stats'],
      'chains' => $data['chains'],
      'filters' => $filters,
    ]);
  }

  /**
   * Show the penalties management
   */
  public function penalties(Request $request)
  {
    $filters = [
      'search' => $request->get('search'),
      'status' => $request->get('status'),
      'per_page' => $request->get('per_page', 10),
    ];

    $data = $this->penaltyService->getPenaltiesList($filters, $filters['per_page']);

    if ($request->wantsJson()) {
      return response()->json([
        'html' => view('admin.partials.penalties-content', [
          'penalties' => $data['penalties'],
          'stats' => $data['stats'],
          'filters' => $filters,
        ])->render(),
        'title' => __('admin.penalties.title')
      ]);
    }

    return view('admin.penalties', [
      'penalties' => $data['penalties'],
      'stats' => $data['stats'],
      'filters' => $filters,
    ]);
  }

  /**
   * Show the reports and analytics
   */
  public function reports(Request $request)
  {
    if ($request->wantsJson()) {
      return response()->json([
        'html' => view('admin.partials.reports-content')->render(),
        'title' => __('admin.reports.title')
      ]);
    }

    return view('admin.reports');
  }

  /**
   * Show the admin profile
   */
  public function profile(Request $request)
  {
    $user = Auth::user();

    if ($request->wantsJson()) {
      return response()->json([
        'html' => view('admin.partials.profile-content', compact('user'))->render(),
        'title' => __('admin.profile.title')
      ]);
    }

    return view('admin.profile', compact('user'));
  }
}
