<?php 

namespace App\Repositories;
use App\Models\Sucursal;

class BaseDatos{

    public function obtenerTodasSucursales(){
        $sucursales = Sucursal::all();
        return $sucursales;
    }
    public function obtenerSucursal($cadena_id,$sucursal_id){
        $sucursales = Sucursal::first()->where('cadena_id',$cadena_id)->where('sucursal_id',$sucursal_id);
        return $sucursales;
    }
}
