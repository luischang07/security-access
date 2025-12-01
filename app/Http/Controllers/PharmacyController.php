<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Repositories\InventarioRepository;
use App\Repositories\PedidoRepository;
use App\Services\Modelos\PedidoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @method void middleware(\Closure|string $middleware)
 */
class PharmacyController extends Controller
{
  private const BRANCH_INFO_NOT_FOUND = 'No se encontró información de la sucursal del empleado.';

  public function __construct(
    private readonly PedidoService $pedidoService,
    private readonly PedidoRepository $pedidoRepository,
    private readonly InventarioRepository $inventarioRepository
  ) {
    $this->middleware(function ($request, $next) {
      /** @var \App\Models\User|null $user */
      $user = Auth::user();

      if (!Auth::check() || !$user || !$user->isPharmacyEmployee()) {
        abort(403, 'No tienes permisos de farmacia para acceder a esta sección.');
      }
      return $next($request);
    });
  }

  /**
   * Show the pharmacy dashboard
   */
  public function dashboard()
  {
    // ✅ SEGURIDAD: Solo datos de la sucursal del empleado
    /** @var \App\Models\User $user */
    $user = Auth::user();
    $branchIds = $user->getBranchIds();

    if (!$branchIds) {
      abort(403, self::BRANCH_INFO_NOT_FOUND);
    }

    $empleado = $user->empleado;

    // Pedidos pendientes de la sucursal
    $pedidosPendientes = $this->pedidoRepository->getPendingOrdersForBranch(
      $branchIds['cadena_id'],
      $branchIds['sucursal_id']
    );

    return view('pharmacy.dashboard', compact('empleado', 'pedidosPendientes'));
  }

  /**
   * Show the pharmacy orders page
   */
  public function orders()
  {

    $user = Auth::user();
    $branchIds = $user->getBranchIds();

    $pedidos = $this->pedidoService->getPedidosSucursal($branchIds['cadena_id'], $branchIds['sucursal_id']);

    return view('pharmacy.orders', compact('pedidos'));
  }

  /**
   * Show the pharmacy inventory management
   */
  public function inventory()
  {
    // ✅ SEGURIDAD: Solo inventario de la sucursal del empleado
    /** @var \App\Models\User $user */
    $user = Auth::user();
    $branchIds = $user->getBranchIds();

    if (!$branchIds) {
      abort(403, self::BRANCH_INFO_NOT_FOUND);
    }

    $inventario = $this->inventarioRepository->getPaginatedInventoryForBranch(
      $branchIds['cadena_id'],
      $branchIds['sucursal_id']
    );

    return view('pharmacy.inventory', compact('inventario'));
  }

  /**
   * Show the pharmacy reports
   */
  public function reports()
  {
    return view('pharmacy.reports');
  }
  /**
   * Show the route for a specific order
   */
  public function showOrderRoute($folio)
  {
    $user = Auth::user();
    $branchIds = $user->getBranchIds();

    if (!$branchIds) {
      abort(403, self::BRANCH_INFO_NOT_FOUND);
    }

    $pedido = Pedido::where('folio_pedido', $folio)
      ->where('cadena_id', $branchIds['cadena_id'])
      ->where('sucursal_id', $branchIds['sucursal_id'])
      ->with([
        'rutaRecoleccion',
        'lineasPedidos.medicamento',
        'lineasPedidos.detalles'
      ])
      ->firstOrFail();

    return view('pharmacy.order-route', compact('pedido'));
  }

  public function marcarComoSurtido($folio)
  {
    $pedido = $this->pedidoService->getPedidoPorFolio($folio);
    if (!$pedido) {
      return response()->json(['error' => 'Pedido no encontrado.'], 404);
    }

    try {
      $this->pedidoService->marcarPedidoComoSurtido($pedido);
      return response()->json(['success' => true, 'message' => 'Pedido marcado como surtido y notificación enviada.']);
    } catch (\Throwable $e) {
      return response()->json(['error' => $e->getMessage()], 400);
    }
  }
}
