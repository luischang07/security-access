<?php


namespace App\Domain;

class DetalleLineaPedido {
    private $precio;
    private $cantidadSurtida;
    private $sucsel;

    public function __construct($precio, $cantidadSurtida, $sucsel){
        $this->precio= $precio;
        $this->cantidadSurtida = $cantidadSurtida;
        $this->sucsel=$sucsel;
    }
    public function getCantidadSurtida(){
        return $this->cantidadSurtida;
    } 
    public function getSucursal(){
        return $this->sucsel;
    }
}
