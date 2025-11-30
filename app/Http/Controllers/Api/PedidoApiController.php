<?php

use App\Http\Controllers\Controller\Api;
use App\Repositories\BaseDatos;
use Illuminate\Http\Request;
use App\Models\Pedido;

class PedidoApiController extends Controller
{
    protected $db;

    public function __construct()
    {
        $this->db = new BaseDatos();
    }
    public function getPedidos($paciente_id)
    {
        try {
            $pedidos = $this->db->getPedidos($paciente_id);

            return response()->json([
                'success' => true,
                'data' => $pedidos
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
