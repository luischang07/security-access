<?php 

namespace App\Http\ServiciosTecnicos;
use App\Http\ServiciosTecnicos\DataBase;
use App\Models\Sucursal;

class BaseDatos{
    
    private $cadena_id,$sucursal_id,$nombre;
    private $calle,$numero_exterior,$numero_interiror,$ciudad,$colonia;
    private $latitud,$longitud;

    //obtener sucursales
    public function obtenerSucursales(){
        $sucursales = Sucursal::all();
        return $sucursales;
    }
    public function obtenerSucursal($cadena_id,$sucursal_id){
        $sucursales = Sucursal::first()->where('cadena_id',$cadena_id)->where('sucursal_id',$sucursal_id);
        return $sucursales;
    }
}
