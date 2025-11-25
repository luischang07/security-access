<?php

namespace App\Services\Modelos;
use App\Repositories\BaseDatos;
use App\Domain\Sucursal;

class SucursalService
{

    private Sucursal $sucursal;
    private BaseDatos $dataBase;

    public function __construct(){
        $this->dataBase=new BaseDatos();
    }
    
    public function obtenerTodasSucursales(){
        return $this->dataBase->obtenerTodasSucursales();
    }

    public function obtenerSucursal($cadena_id,$sucursal_id){
        return $this->dataBase->obtenerSucursal($cadena_id,$sucursal_id);
    }

    public function getLineaInventario($medId,$cadena_id,$sucursal_id){
        return $this->dataBase->obtenerInventario($medId,$cadena_id,$sucursal_id);
    }
}
