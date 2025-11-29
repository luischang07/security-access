<?php

namespace App\Services\Modelos;
use App\Repositories\BaseDatos;
use App\Domain\Sucursal;

class SucursalService
{

    private Sucursal $sucursal;
    private BaseDatos $dataBase;

    public function __construct()
    {
        $this->dataBase = new BaseDatos();
    }

    public function obtenerTodasSucursales()
    {
        return $this->dataBase->obtenerTodasSucursales();
    }

    public function obtenerSucursal($cadena_id, $sucursal_id)
    {
        return $this->dataBase->obtenerSucursal($cadena_id, $sucursal_id);
    }

    public function getLineaInventario($cadena_id, $sucursal_id, $medicamento_id)
    {
        return $this->dataBase->obtenerInventario($cadena_id, $sucursal_id, $medicamento_id);
    }

    public function calculaSucCercanas($cadena_id, $sucursal_id)
    {
        return $this->dataBase->obtenerSucursalesOrdenadas($cadena_id, $sucursal_id);
    }

    public function actualizarInventario($ldi)
    {
        return $this->dataBase->actualizarInventario($ldi);
    }

    public function obtenerInventarioWithUpdate($cantidadSurtida, $medid, $sucursal)
    {
        $ldi = $this->dataBase->obtenerInventarioWithUpdate($cantidadSurtida, $medid, $sucursal);
        return $ldi;
    }

}
