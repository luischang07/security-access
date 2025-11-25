<?php 

namespace App\Repositories;
use App\Models\Sucursal;
use App\Models\Inventario;
use App\Models\CadenaFarmaceutica;
use App\Domain\LineaInventario;
class BaseDatos{

    public function obtenerTodasSucursales(){
        $sucursales = Sucursal::all();
        return $sucursales;
    }
    public function obtenerSucursal($cadena_id,$sucursal_id){
        $sucursal = Sucursal::first()->where('cadena_id',$cadena_id)->where('sucursal_id',$sucursal_id);

        $sucursal = Sucursal::crear($sucursal);
        return $sucursal;
    }

    public function obtenerInventario($medId,$cadena_id,$sucursal_id){
        $data=Inventario::first()->where('cadena_id',$cadena_id)->where('sucursal_id',$sucursal_id)->where('medicamento_id',$medId);

        return new LineaInventario($data->cadena_id, $data->sucursal_id, $data->medicamento_id, $data->stock_disponible,$data->precio_unitario);
    }
    public function obtenerCadenas()
    {
        return CadenaFarmaceutica::select('cadena_id', 'nombre')->orderBy('nombre')->get();
    }
    // obtener sucursales por cadena
    public function obtenerSucursalesPorCadena($cadena_id)
    {
        return Sucursal::where('cadena_id', $cadena_id)->get();
    }
}
