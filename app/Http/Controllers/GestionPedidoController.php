<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\Modelos\PedidoService;
use App\Services\Modelos\SucursalService;
use App\Services\Modelos\CadenaService;
use App\Services\Modelos\GestorDeSurtido;
use App\Domain\Pedido;
use Illuminate\Support\Facades\Session;
use App\Models\Medicamento;


class GestionPedidoController extends Controller
{
    private PedidoService $pedidoService;
    private SucursalService $sucursalService;
    private GestorDeSurtido $GestorDeSurtido;
    private CadenaService $cadenaService;

    public function __construct(PedidoService $pedidoService,SucursalService $sucursalService,GestorDeSurtido $GestorDeSurtido, CadenaService $cadenaService){
        $this->pedidoService = $pedidoService;
        $this->sucursalService = $sucursalService;
        $this->GestorDeSurtido = $GestorDeSurtido;
        $this->cadenaService = $cadenaService;
    }
    
    public function nuevoPedido(){

        $paciente_id = Auth::user()->user_id;

        $pedido = $this->pedidoService->nuevoPedido($paciente_id);
        $cadenas = $this->cadenaService->obtenerTodasCadenas();

        return view('prescription.upload-step1', compact('cadenas'));
    }


    public function seleccionarSucursal(Request $request){

        $sucursal_id = $request->input('sucursal_id');
        $cadena_id = $request->input('cadena_id');
        $this->pedidoService->asociarSucursalAPedido($cadena_id,$sucursal_id);

        return response()->json(['ok' => true]);
    }


    public function agregarMedicamento(Request $request){
        $medId = (int) $request->input('medId');
        $cantidad = (int) $request->input('cantidad');

        if ($medId <= 0 || $cantidad < 1) {
            return response()->json(['ok' => false, 'message' => 'Datos de medicamento inválidos'], 422);
        }

        try {
            $this->pedidoService->agregarMedicamento($medId,$cantidad);
            return response()->json([
                'ok' => true,
                'medications' => $this->lineasPedidoActuales()
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
            'medications' => $this->lineasPedidoActuales()
        ]);
    }

    public function confirmarCaptura(){
        $paciente_id = Auth::user()->user_id;

        $pedido = $this->pedidoService->nuevoPedido($paciente_id);
        $pedido->asociarSucursalAPedido("CAD001","SUC001");

        $datosPedido = Session::get('pedido_temporal', []);


        $pedido = Pedido::createPedidoFromSession($datosPedido);

        $this->GestorDeSurtido->surtir($pedido);
    }

    public function buscarMedicamentos(Request $request){
        $query = $request->input('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $medicamentos = Medicamento::where('nombre', 'like', '%' . $query . '%')
            ->orderBy('nombre')
            ->limit(10)
            ->get(['id','nombre','unidad_medida','unidades']);

        return response()->json($medicamentos);
    }

    private function lineasPedidoActuales(): array
    {
        $datosPedido = Session::get('pedido_temporal');

        if (!$datosPedido || empty($datosPedido['lineas_pedido'])) {
            return [];
        }

        $lineas = collect($datosPedido['lineas_pedido']);
        $medicamentos = Medicamento::whereIn('id', $lineas->pluck('medicamento_id'))
            ->get(['id','nombre'])
            ->keyBy('id');

        return $lineas->map(function ($linea) use ($medicamentos) {
            $med = $medicamentos->get($linea['medicamento_id']);
            return [
                'id' => $linea['medicamento_id'],
                'name' => $med->nombre ?? 'Medicamento ' . $linea['medicamento_id'],
                'quantity' => (int) $linea['cantidad'],
            ];
        })->values()->all();
    }
}
