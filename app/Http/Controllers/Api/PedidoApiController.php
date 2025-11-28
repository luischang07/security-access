<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Modelos\PedidoService;
use Illuminate\Http\Request;

class PedidoApiController extends Controller
{
    private PedidoService $pedidoService;

    public function __construct(PedidoService $pedidoService)
    {
        $this->pedidoService = $pedidoService;
    }

    /**
     * ✔ API: Obtener TODOS los pedidos de un paciente
     */
    public function pedidosPorPaciente($paciente_id)
    {
        $pedidos = $this->pedidoService->obtenerPedidosPorPacienteId($paciente_id);

        return response()->json([
            'ok' => true,
            'pedidos' => $pedidos
        ]);
    }

    /**
     * ✔ API: Obtener un pedido con detalle
     */
    public function pedidoPorFolio($folio)
    {
        $pedido = $this->pedidoService->obtenerPedidoPorFolio($folio);

        if (!$pedido) {
            return response()->json([
                'ok' => false,
                'message' => 'Pedido no encontrado'
            ], 404);
        }

        return response()->json([
            'ok' => true,
            'pedido' => $pedido
        ]);
    }
}