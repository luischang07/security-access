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

        return view('prescription.upload-step1', compact('cadenas', ));
    }


    public function seleccionarSucursal(Request $request)
    {

        $sucursal_id = $request->input('sucursal_id');
        $cadena_id = $request->input('cadena_id');
        $this->pedidoService->asociarSucursalAPedido($cadena_id, $sucursal_id);

        return response()->json(['ok' => true]);
    }


    public function agregarMedicamento(Request $request)
    {
        $medId = (int) $request->input('medId');
        $cantidad = (int) $request->input('cantidad');

        if ($medId <= 0 || $cantidad < 1) {
            return response()->json(['ok' => false, 'message' => 'Datos de medicamento inválidos'], 422);
        }



        try {
            $this->pedidoService->agregarMedicamento($medId, $cantidad);
            return response()->json([
                'ok' => true,
                'medications' => $this->pedidoService->obtenerLineasPedidoActuales()
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

        try {
            $this->pedidoService->eliminarMedicamento($medId);
        } catch (\Throwable $e) {
            return response()->json(['ok' => false, 'message' => $e->getMessage()], 400);
        }

        return response()->json([
            'ok' => true,
            'medications' => $this->pedidoService->obtenerLineasPedidoActuales()
        ]);
    }

    public function confirmarCaptura()
    {
        $paciente_id = Auth::user()->user_id;

        $pedido = unserialize(Session::get('pedido_temporal'));

        $pedido = $this->GestorDeSurtido->surtir($pedido);

        Session::put('pedido_temporal', serialize($pedido));
        return view('prescription.upload-step2', compact('pedido'));
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
}
