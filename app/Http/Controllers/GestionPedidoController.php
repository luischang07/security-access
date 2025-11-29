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


class GestionPedidoController extends Controller
{
    private PedidoService $pedidoService;
    private SucursalService $sucursalService;
    private GestorDeSurtido $GestorDeSurtido;
    private CadenaService $cadenaService;
    private MedicamentoService $medicamentoService;

    public function __construct(PedidoService $pedidoService, SucursalService $sucursalService, GestorDeSurtido $GestorDeSurtido, CadenaService $cadenaService, MedicamentoService $medicamentoService)
    {
        $this->pedidoService = $pedidoService;
        $this->sucursalService = $sucursalService;
        $this->GestorDeSurtido = $GestorDeSurtido;
        $this->cadenaService = $cadenaService;
        $this->medicamentoService = $medicamentoService;
    }


    public function nuevoPedido()
    {
        $paciente_id = Auth::user()->user_id;


        $pedido = $this->pedidoService->nuevoPedido($paciente_id);

        Session::put('pedido_temporal', serialize($pedido));

        $cadenas = $this->cadenaService->obtenerTodasCadenas();

        return view('prescription.upload-step1', compact('cadenas'));
    }


    public function seleccionarSucursal(Request $request)
    {

        $sucursal_id = $request->input('sucursal_id');
        $cadena_id = $request->input('cadena_id');
        $sucursal = $this->sucursalService->obtenerSucursal($cadena_id, $sucursal_id);
        $pedido = unserialize(Session::get('pedido_temporal'));
        Session::forget('pedido_temporal');
        $pedido = $this->pedidoService->asociarSucursalAPedido($sucursal, $pedido);

        Session::put('pedido_temporal', serialize($pedido));
        return response()->json(['ok' => true]);
    }


    public function agregarMedicamento(Request $request)
    {
        $medId = (int) $request->input('medId');
        $cantidad = (int) $request->input('cantidad');

        if ($medId <= 0 || $cantidad < 1) {
            return response()->json(['ok' => false, 'message' => 'Datos de medicamento inválidos'], 422);
        }

        $pedido = unserialize(Session::get('pedido_temporal'));
        Session::forget('pedido_temporal');

        try {
            $pedido = $this->pedidoService->agregarMedicamento($medId, $cantidad, $pedido);

            Session::put('pedido_temporal', serialize($pedido));
            return response()->json([
                'ok' => true,
                'medications' => $this->pedidoService->obtenerLineasPedidoActuales($pedido)
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

        Session::forget('pedido_temporal');
        try {
            $pedido = $this->pedidoService->eliminarMedicamento($medId, $pedido);

            Session::put('pedido_temporal', serialize($pedido));

        } catch (\Throwable $e) {
            return response()->json(['ok' => false, 'message' => $e->getMessage()], 400);
        }

        return response()->json([
            'ok' => true,
            'medications' => $this->pedidoService->obtenerLineasPedidoActuales($pedido)
        ]);
    }

    public function confirmarCaptura(Request $request)
    {
        $cedulaProfesional = $request->input("cedula_profesional");

        $paciente_id = Auth::user()->user_id;

        $pedido = unserialize(Session::get('pedido_temporal'));
        Session::forget('pedido_temporal');
        $pedido = $this->GestorDeSurtido->surtir($pedido);
        $pedido = $this->pedidoService->asignarFechaRecoleccion($pedido);
        $pedido = $this->pedidoService->setCedulaProfesional($cedulaProfesional, $pedido);
        Session::put('pedido_temporal', serialize($pedido));
        return view('prescription.upload-step2', compact('pedido'));
    }

    public function confirmarPedido()
    {
        $pedido = unserialize(Session::get('pedido_temporal'));
        info("Confirmando pedido...");
        $pedido = $this->GestorDeSurtido->confirmarPedido($pedido);
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

    public function getPedidos()
    {
        $pedidos = $this->pedidoService->obtenerPedidosPorPacienteId(Auth::user()->user_id);

        return view('patient.orders', compact('pedidos'));
    }

    public function getPedido($id)
    {
        // $id is the folio_pedido of the Pedido
        $pedido = $this->pedidoService->obtenerPedidoPorFolio($id);

        if (!$pedido) {
            abort(404);
        }

        return view('patient.order-detail', compact('pedido'));
    }
}
