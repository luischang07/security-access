<?php


namespace App\Http\Dominio;

class Pedido{

    private $cedulaProfesional;
    private $fecha_pedido,$fecha_recoleccion,$fecha_entrega;
    private $estatus;
    private $lineas_pedido;
    private $paciente,$sucursal;
    
    public function __construct($paciente){
        $this->paciente=$paciente;
        $this->lineas_pedido=array();
    }
    public function asociarSucursalAPedido($sucursal){
        $this->sucursal=$sucursal;
        
    }
}   