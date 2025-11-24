<?php

namespace App\Http\Controllers;

use App\Repositories\PedidoRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @method void middleware(\Closure|string $middleware)
 */
class PatientController extends Controller
{
  public function __construct(
    private readonly PedidoRepository $pedidoRepository
  ) {
    $this->middleware(function ($request, $next) {
      /** @var \App\Models\User|null $user */
      $user = Auth::user();

      if (!Auth::check() || !$user || !$user->isPatient()) {
        abort(403, 'No tienes permisos de paciente para acceder a esta sección.');
      }
      return $next($request);
    });
  }

  /**
   * Show the patient dashboard
   */
  public function dashboard(Request $request)
  {
    $user = Auth::user();
    $userId = $user->user_id;
    $paciente = $user->paciente;

    // Obtener pedidos activos del paciente
    $pedidosActivos = $this->pedidoRepository->getActiveOrdersForPatient($userId);

    // Preparar datos de progreso para cada pedido activo
    $pedidosActivosConProgreso = $pedidosActivos->map(function ($pedido) {
      return [
        'pedido' => $pedido,
        'progress' => $this->getOrderProgress($pedido->estatus)
      ];
    });

    // Calcular estadísticas
    $pedidosCompletados = $this->pedidoRepository->getCompletedOrdersCount($userId);
    $pedidosCancelados = $this->pedidoRepository->getCancelledOrdersCount($userId);

    // Obtener historial reciente (últimos 3 pedidos completados o cancelados)
    $historialReciente = $this->pedidoRepository->getRecentHistory($userId, 3);

    // If AJAX request, return only content
    if ($request->ajax() || $request->wantsJson()) {
      return response()->json([
        'html' => view('patient.partials.dashboard-content', compact(
          'pedidosActivosConProgreso',
          'paciente',
          'pedidosCompletados',
          'pedidosCancelados',
          'historialReciente'
        ))->render()
      ]);
    }

    return view('patient.dashboard', compact(
      'pedidosActivosConProgreso',
      'paciente',
      'pedidosCompletados',
      'pedidosCancelados',
      'historialReciente'
    ));
  }

  /**
   * Show the patient orders page
   */
  public function orders(Request $request)
  {
    $user = Auth::user();
    $userId = $user->user_id;
    $pedidos = $this->pedidoRepository->getPaginatedOrdersForPatient($userId);

    // If AJAX request, return only content
    if ($request->ajax() || $request->wantsJson()) {
      return response()->json([
        'html' => view('patient.partials.orders-content', compact('pedidos'))->render()
      ]);
    }

    return view('patient.orders', compact('pedidos'));
  }

  /**
   * Show the patient order history
   */
  public function orderHistory()
  {
    $user = Auth::user();
    $userId = $user->user_id;
    $historial = $this->pedidoRepository->getOrderHistoryForPatient($userId);

    return view('patient.order-history', compact('historial'));
  }

  /**
   * Show the patient profile
   */
  public function profile()
  {
    $user = Auth::user();
    $paciente = $user->paciente;

    // Get statistics
    $totalOrders = $this->pedidoRepository->getPaginatedOrdersForPatient($user->user_id, 1)->total();
    $activePenalties = $paciente->penalties()->where('status', 'active')->count();

    return view('patient.profile', compact('user', 'paciente', 'totalOrders', 'activePenalties'));
  }

  /**
   * Update the patient profile
   */
  public function updateProfile(Request $request)
  {
    $user = Auth::user();

    // Validate input
    $validated = $request->validate([
      'nombre' => 'required|string|max:255',
      'apellido' => 'required|string|max:255',
      'correo' => 'required|email|max:255|unique:users,correo,' . $user->user_id . ',user_id',
    ]);

    try {
      // Update user information
      $user->update([
        'nombre' => $validated['nombre'],
        'apellido' => $validated['apellido'],
        'correo' => $validated['correo'],
      ]);

      return redirect()->route('patient.profile')
        ->with('success', __('patient.profile.messages.update_success'));
    } catch (\Exception $e) {
      return redirect()->route('patient.profile')
        ->with('error', __('patient.profile.messages.update_error'))
        ->withInput();
    }
  }

  /**
   * Show the patient penalties
   */
  public function penalties()
  {
    $user = Auth::user();
    $paciente = $user->paciente;

    return view('patient.penalties', compact('paciente'));
  }

  /**
   * Show the patient help page
   */
  public function help()
  {
    return view('patient.help');
  }

  /**
   * Get order progress data based on status
   * 
   * @param string $estatus
   * @return array
   */
  private function getOrderProgress(string $estatus): array
  {
    return match ($estatus) {
      'pendiente' => [
        'width' => '25%',
        'color' => 'bg-yellow-400',
        'label' => __('patient.dashboard.active_orders.awaiting_confirmation'),
      ],
      'en_proceso' => [
        'width' => '50%',
        'color' => 'bg-blue-500',
        'label' => __('patient.dashboard.active_orders.in_process'),
      ],
      'listo' => [
        'width' => '75%',
        'color' => 'bg-green-500',
        'label' => __('patient.dashboard.active_orders.ready_for_pickup'),
      ],
      'entregado' => [
        'width' => '100%',
        'color' => 'bg-green-600',
        'label' => __('patient.dashboard.active_orders.delivered'),
      ],
      default => [
        'width' => '0%',
        'color' => 'bg-gray-400',
        'label' => ucfirst($estatus)
      ],
    };
  }
}
