<?php

namespace App\Http\Controllers;
use App\Services\Modelos\PedidoService;
use App\Services\Modelos\SucursalService;
use App\Services\Modelos\GestorDeSurtido;
use App\Services\Modelos\CadenaService;
use App\Services\Modelos\MedicamentoService;
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

    public function cancelarPorFolio($folio)
    {
        $pedido = $this->pedidoService->obtenerPedidoPorFolio($folio);
        if (!$pedido) {
            return response()->json(['error' => 'Pedido no encontrado.'], 404);
        }
        $this->pedidoService->cancelarPedido($pedido);
        return response()->json(['success' => true]);
    }
}
