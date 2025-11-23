<?php 

namespace App\Http\ServiciosTecnicos;
use App\Http\ServiciosTecnicos\DataBase;
use App\Models\Sucursal;

class DataBase{
    
    private $cadena_id,$sucursal_id,$nombre;
    private $calle,$numero_exterior,$numero_interiror,$ciudad,$colonia;
    private $latitud,$longitud;

    //obtener sucursales
    public function obtenerSucursales(){
        $sucursales = Sucursal::all();
        return $sucursales;
    }
}
