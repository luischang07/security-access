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
          'cadena_id'    => 'required',
          'sucursal_id'  => 'required',
      ]);
      $pedido = $this->pedidoService->buscarPorFolio(
          $request->input('folio_pedido'),
          $request->input('cadena_id'),
          $request->input('sucursal_id')
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
            'cadena_id'     => $pedido->cadena_id,
            'sucursal_id'   => $pedido->sucursal_id,
            'estatus'       => $pedido->estatus,
            'fecha_pedido'  => $pedido->fecha_pedido,
            'costo_total'   => $pedido->costo_total,
        ]);

    }
}