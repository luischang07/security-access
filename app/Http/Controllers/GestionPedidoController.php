<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Dominio\ModeloDominio;

class GestionPedidoController extends Controller
{
    private ModeloDominio $modeloDominio;

    public function __construct(ModeloDominio $modeloDominio){
        $this->modeloDominio = $modeloDominio;
    }
    public function nuevoPedido($paciente){
        $this->modeloDominio->nuevoPedido($paciente);
        return $this->modeloDominio;
    }
    public function seleccionarSucursal(Request $request){

        $sucursalId = $request->input('sucursal_id');
        $cadenaId = $request->input('cadena_id');


        // info([$sucursalId, $cadenaId]);
        //     $this->modeloDominio->asociarSucursalAPedido($sucursal);

        return null;
    }
    
    public function agregarMedicamento($medId,$cantidad){
        $this->modeloDominio->agregarMedicamento($medId,$cantidad);
    }
}
