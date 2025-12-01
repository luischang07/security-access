<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\Modelos\PedidoService;
use App\Services\Modelos\SucursalService;
use App\Services\Modelos\CadenaService;
use App\Services\Modelos\GestorDeSurtido;
use App\Services\Modelos\MedicamentoService;
use App\Domain\Pedido;
use Illuminate\Support\Facades\Session;
use App\Services\Modelos\PacienteService;


class GestionPedidoController extends Controller
{
  private PedidoService $pedidoService;

  private PacienteService $pacienteService;
  private SucursalService $sucursalService;
  private GestorDeSurtido $GestorDeSurtido;
  private CadenaService $cadenaService;
  private MedicamentoService $medicamentoService;

  public function __construct(PedidoService $pedidoService, SucursalService $sucursalService, GestorDeSurtido $GestorDeSurtido, CadenaService $cadenaService, MedicamentoService $medicamentoService, PacienteService $pacienteService)
  {
    $this->pedidoService = $pedidoService;
    $this->sucursalService = $sucursalService;
    $this->GestorDeSurtido = $GestorDeSurtido;
    $this->cadenaService = $cadenaService;
    $this->medicamentoService = $medicamentoService;
    $this->pacienteService = $pacienteService;
  }

  public function nuevoPedido(Request $request)
  {
    $paciente_id = Auth::user()->user_id;
    try {
      $pedido = $this->pedidoService->nuevoPedido($paciente_id);
      $pedido = Session::has('pedido_temporal') ? unserialize(Session::get('pedido_temporal')) : null;
      if (!$pedido || $request->boolean('reset')) {
        $pedido = $this->pedidoService->nuevoPedido($paciente_id);
      } else {
        // Reutiliza lo que el paciente ya capturó pero limpia detalles/ruta para volver a surtir
        $pedido = $this->pedidoService->reiniciarParaCaptura($pedido);
      }

      Session::put('pedido_temporal', serialize($pedido));

      $cadenas = $this->cadenaService->getCadenas();

      $formData = Session::get('prescription_form_data', []);

      return view('prescription.upload-step1', [
        'cadenas' => $cadenas,
        'pedidoInicial' => $pedido,
        'formData' => $formData
      ]);
    } catch (\Throwable $e) {
      return redirect()->route('patient.dashboard')->with('error', 'No puedes realizar pedidos mientras tengas una penalización pendiente y un pedido activo');
    }
  }

  public function seleccionarSucursal(Request $request)
  {
    $sucursal_id = $request->input('sucursal_id');
    $cadena_id = $request->input('cadena_id');
    $sucursal = $this->sucursalService->getSucursal($cadena_id, $sucursal_id);
    $pedido = unserialize(Session::get('pedido_temporal'));

    if (!$pedido instanceof Pedido) {
      return response()->json(['ok' => false, 'message' => 'Sesión expirada'], 401);
    }

    Session::forget('pedido_temporal');
    $pedido = $this->pedidoService->asociarSucursalAPedido($sucursal, $pedido);

    Session::put('pedido_temporal', serialize($pedido));
    return response()->json(['ok' => true]);
  }

  public function buscarMedicamentos(Request $request)
  {
    $query = $request->input('q', '');

    if (strlen($query) < 2) {
      return response()->json([]);
    }

    $medicamentos = $this->medicamentoService->obtenerMedicamentosPorNombre($query);

    return response()->json($medicamentos);
  }

  public function getSucursalesPorCadena($cadena_id)
  {
    $sucursales = $this->sucursalService->getSucursalesPorCadena($cadena_id);
    return response()->json($sucursales);
  }

  public function agregarMedicamento(Request $request)
  {
    $medId = (int) $request->input('medId');
    $cantidad = (int) $request->input('cantidad');

    if ($medId <= 0 || $cantidad < 1) {
      return response()->json(['ok' => false, 'message' => 'Datos de medicamento inválidos'], 422);
    }

    $pedido = unserialize(Session::get('pedido_temporal'));

    if (!$pedido instanceof Pedido) {
      return response()->json(['ok' => false, 'message' => 'Sesión expirada'], 401);
    }

    Session::forget('pedido_temporal');

    try {
      $pedido = $this->pedidoService->agregarMedicamento($medId, $cantidad, $pedido);

      Session::put('pedido_temporal', serialize($pedido));
      return response()->json([
        'ok' => true,
        'medications' => $this->pedidoService->getLineasPedidoActuales($pedido)
      ]);
    } catch (\Throwable $e) {
      return response()->json(['ok' => false, 'message' => $e->getMessage()], 400);
    }
  }

  public function eliminarMedicamento(Request $request)
  {
    $medId = (int) $request->input('medId');

    if ($medId <= 0) {
      return response()->json(['ok' => false, 'message' => 'ID de medicamento inválido'], 422);
    }
    $pedido = unserialize(Session::get('pedido_temporal'));

    if (!$pedido instanceof Pedido) {
      return response()->json(['ok' => false, 'message' => 'Sesión expirada'], 401);
    }

    Session::forget('pedido_temporal');
    try {
      $pedido = $this->pedidoService->eliminarMedicamento($medId, $pedido);

      Session::put('pedido_temporal', serialize($pedido));
    } catch (\Throwable $e) {
      return response()->json(['ok' => false, 'message' => $e->getMessage()], 400);
    }

    return response()->json([
      'ok' => true,
      'medications' => $this->pedidoService->getLineasPedidoActuales($pedido)
    ]);
  }

  public function confirmarCaptura(Request $request)
  {
    $cedulaProfesional = $request->input("cedula_profesional");

    $paciente_id = Auth::user()->user_id;

    $pedido = unserialize(Session::get('pedido_temporal'));

    if (!$pedido instanceof Pedido) {
      return redirect()->route('prescription.upload.step1')->with('error', 'La sesión del pedido ha expirado. Por favor inicie de nuevo.');
    }

    Session::put('prescription_form_data', [
      'cedula_profesional' => $cedulaProfesional,
      'cadena_id' => $pedido->getSucursal()->getCadenaId(),
      'sucursal_id' => $pedido->getSucursal()->getSucursalId(),
      'medications' => $this->pedidoService->getLineasPedidoActuales($pedido)
    ]);

    Session::forget('pedido_temporal');
    $pedido = $this->pedidoService->asignarFechaRecoleccion($pedido);
    $pedido = $this->pedidoService->setCedulaProfesional($cedulaProfesional, $pedido);
    $montoPenalizacion = $this->pacienteService->getMontoPenalizacion($paciente_id);
    $pedido = $this->GestorDeSurtido->surtir($pedido);

    $porcentajeSurtido = $pedido->calcularPorcentajeSurtido();
    if ($porcentajeSurtido < 0.5) {

      Session::put('pedido_temporal', serialize($pedido));

      return redirect()->route('prescription.upload.step1')
        ->with('error', 'No se puede surtir al menos el 50% de tu receta. Por favor intenta reducir los medicamentos solicitados.');
    }

    Session::put('pedido_temporal', serialize($pedido));
    return view('prescription.upload-step2', compact('pedido', 'montoPenalizacion'));
  }

  public function confirmarPedido()
  {
    $pedido = unserialize(Session::get('pedido_temporal'));

    if (!$pedido instanceof Pedido) {
      return redirect()->route('prescription.upload.step1')->with('error', 'La sesión del pedido ha expirado.');
    }

    Session::forget('pedido_temporal');
    Session::forget('prescription_form_data');

    try {
      $pedido = $this->GestorDeSurtido->confirmarPedido($pedido);
      $folio = $pedido->getFolio();
      return redirect("/patient/orders/{$folio}")->with('order_success', true);
    } catch (\Throwable $e) {
      Session::put('pedido_temporal', serialize($pedido));
      return redirect()->back()->withErrors($e->getMessage());
    }
  }


  public function getPedidos()
  {
    $pedidos = $this->pedidoService->getPedidosPorPacienteId(Auth::user()->user_id);

    return view('patient.orders', compact('pedidos'));
  }

  public function getPedido($folio)
  {
    $pedido = $this->pedidoService->getPedidoPorFolio($folio);

    if (!$pedido) {
      abort(404);
    }

    return view('patient.order-detail', compact('pedido'));
  }
}
