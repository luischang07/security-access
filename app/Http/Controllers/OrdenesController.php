<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\Modelos\PedidoService;

class OrdenesController extends Controller
{
    private PedidoService $pedidoService;

    public function __construct(PedidoService $pedidoService)
    {
        $this->pedidoService = $pedidoService;
    }

  public function buscarPorFolio(Request $request)
    {
      $request->validate([
          'folio_pedido' => 'required',
          'apellido'    => 'required',
      ]);
      $pedido = $this->pedidoService->buscarPorFolio(
          $request->input('folio_pedido'),
          $request->input('apellido'),
      );
      if (!$pedido) {
          return response()->json([
              'encontrado' => false,
              'mensaje'    => 'Pedido no encontrado',
          ], 404);
      }

    return response()->json([
        'encontrado'    => true,
        'folio_pedido'  => $pedido->folio_pedido,
        'estatus'       => $pedido->estatus,
        'fecha_pedido'  => $pedido->fecha_pedido->format('Y-m-d'),
        'costo_total'   => $pedido->costo_total,
        // Datos del paciente
        'paciente'      => [
            'id'       => $pedido->paciente->id ?? null,
            'nombre'   => $user->name ?? null,
            'apellido' => $user->apellido ?? null,
        ],
    ]);

    }
}