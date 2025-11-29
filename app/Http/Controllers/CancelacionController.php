<?php

namespace App\Http\Controllers;
use App\Services\Modelos\PedidoService;
use App\Domain\Pedido;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class CancelacionController extends Controller
{
    private PedidoService $pedidoService;

    public function __construct(PedidoService $pedidoService, SucursalService $sucursalService, GestorDeSurtido $GestorDeSurtido, CadenaService $cadenaService, MedicamentoService $medicamentoService)
    {
        $this->pedidoService = $pedidoService;
    }

    public function confirmarCancelacion(Request $request)
    {
        $pedido_id = $request->input('pedido_id');
        $pedido = $this->pedidoService->buscarPedidoPorId($pedido_id);
        if (!$pedido) {
            return response()->json(['error' => 'Pedido no encontrado.'], 404);
        }
        $this->pedidoService->cancelarPedido($pedido);

    }
}
