<?php

namespace App\Domain;

use App\Domain\DetalleLineaPedido;

use App\Domain\Medicamento;
use Illuminate\Support\Collection;
class LineaPedido {
    private $cantidad;
    private $medicamento_id;
    private $medicamento;
    private $detalleLineaPedido;

    public function __construct($medicamentoId,$cantidad, $medicamento) {
        $this->medicamento_id=$medicamentoId;
        $this->cantidad=$cantidad;
        $this->medicamento=$medicamento;
        $this->detalleLineaPedido = collect();
    }

    public function getCantidad(){
        return $this->cantidad;
    }
    public function getMedicamentoId(){
        return $this->medicamento_id;
    }

    public function getMedicamento(){
    return $this->medicamento;
    }
    
    public function setCantidad($cantidad){
        $this->cantidad=$cantidad;
    }
    public function setMedicamentoId($medicamentoId){
        $this->medicamento_id=$medicamentoId;
    }
    public function setStockDisponible($stock_disponible){
    $this->stock_disponible=$stock_disponible;
    }

    //Crear detalle linea de pedido
    public function crearDetalleLineaPedido($precio, $cantidadSurtida, $sucsel)
    {
        $dlp = new DetalleLineaPedido($precio, $cantidadSurtida, $sucsel);
        $this->detalleLineaPedido->push($dlp);
    }   

    public function getCantidadFaltante(){
        $faltante=$this->cantidad;
        foreach($this->detalleLineaPedido as $detalle){
            $faltante -= $detalle->getCantidadSurtida();
        }
        return $faltante;
    }

    public function getDetalleLineaPedido(){
        return $this->detalleLineaPedido;
    }

    // Retornar todos los detalles (colección) para poder iterar y calcular totales
    public function getDetalles()
    {
        return $this->detalleLineaPedido;
    }
}
