<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;

class PedidoApiController extends Controller
{
    // Buscar un pedido por su folio
    public function mostrar($folio)
    {
        $pedido = Pedido::where('folio_pedido', $folio)->first();

        if (!$pedido) {
            return response()->json([
                'success' => false,
                'message' => 'Pedido no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'folio' => $pedido->folio_pedido,
            'estatus' => $pedido->estatus,
            'fecha_pedido' => $pedido->fecha_pedido,
            'fecha_recoleccion' => $pedido->fecha_recoleccion,
            'costo_total' => $pedido->costo_total
        ]);
    }

    // Buscar pedidos por ID del paciente
    public function porPaciente($id)
    {
        $pedidos = Pedido::where('paciente_id', $id)->get();

        return response()->json([
            'success' => true,
            'pedidos' => $pedidos
        ]);
    }
}
