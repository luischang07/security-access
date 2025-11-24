<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\Modelos\PedidoService;
use App\Services\Modelos\SucursalService;

class GestionPedidoController extends Controller
{
    private PedidoService $pedidoService;
    private SucursalService $sucursalService;

    public function __construct(PedidoService $pedidoService,SucursalService $sucursalService){
        $this->pedidoService = $pedidoService;
        $this->sucursalService = $sucursalService;
    }
    
    public function nuevoPedido(){

        $paciente_id = Auth::user()->user_id;

        $pedido = $this->pedidoService->nuevoPedido($paciente_id);
        $sucursales = $this->sucursalService->obtenerTodasSucursales();

        return view('prescription.upload-step1', compact('sucursales'));
    }
    public function seleccionarSucursal(Request $request){

        $sucursal_id = $request->input('sucursal_id');
        $cadena_id = $request->input('cadena_id');

        $this->pedidoService->asociarSucursalAPedido($cadena_id,$sucursal_id);

        return true;
    }

    public function agregarMedicamento(Request $request){
        $medId = $request->input('medId');
        $cantidad = $request->input('cantidad');
        $this->pedidoService->agregarMedicamento($medId,$cantidad);
    }
}
