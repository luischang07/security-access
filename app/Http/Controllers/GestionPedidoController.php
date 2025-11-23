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

        $sucursal_id = $request->input('sucursal_id');
        $cadena_id = $request->input('cadena_id');
        $this->modeloDominio->asociarSucursalAPedido($cadena_id,$sucursal_id);
        
    }
    
    public function agregarMedicamento($medId,$cantidad){
        $this->modeloDominio->agregarMedicamento($medId,$cantidad);
    }
}
