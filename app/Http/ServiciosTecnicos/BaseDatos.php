<?php

namespace App\Http\ServiciosTecnicos;

use App\Http\ServiciosTecnicos\DataBase;
use App\Models\Sucursal;
use App\Models\CadenaFarmaceutica;

class BaseDatos
{

  private $cadena_id, $sucursal_id, $nombre;
  private $calle, $numero_exterior, $numero_interiror, $ciudad, $colonia;
  private $latitud, $longitud;

  //obtener sucursales
  public function obtenerSucursales()
  {
    $sucursales = Sucursal::all();
    return $sucursales;
  }

  // obtener cadenas (farmacéuticas)
  public function obtenerCadenas()
  {
    return CadenaFarmaceutica::select('cadena_id', 'nombre')->orderBy('nombre')->get();
  }
  public function obtenerSucursal($cadena_id, $sucursal_id)
  {
    return Sucursal::where('cadena_id', $cadena_id)->where('sucursal_id', $sucursal_id)->first();
  }

  // obtener sucursales por cadena
  public function obtenerSucursalesPorCadena($cadena_id)
  {
    return Sucursal::where('cadena_id', $cadena_id)->get();
  }
}
