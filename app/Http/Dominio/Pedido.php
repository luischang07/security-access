<?php


namespace App\Http\Dominio;

class Pedido{

    private $cedulaProfesional;
    private $fecha_pedido,$fecha_recoleccion,$fecha_entrega;
    private $estatus;
    private $lineas_pedido=[];
    private $paciente,$sucursal;


}