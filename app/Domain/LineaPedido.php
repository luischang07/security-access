<?php

namespace App\Domain;

use App\Models\DetalleLineaPedido;
use Illuminate\Support\Collection;
class LineaPedido {
    private $folio_pedido;
    private $cantidad;
    private $medicamento_id;
    private $stock_disponible;
    private $detalleLineaPedido;

    public function __construct($folio_pedido,$medicamentoId,$cantidad,$stock_disponible){
        $this->folio_pedido=$folio_pedido;
        $this->medicamento_id=$medicamentoId;
        $this->cantidad=$cantidad;
        $this->detalleLineaPedido = collect();
    }

    public function getCantidad(){
    return $this->cantidad;
    }
    public function getMedicamentoId(){
    return $this->medicamento_id;
    }
    
    public function setCantidad($cantidad){
    $this->cantidad=$cantidad;
    }
    public function setMedicamentoId($medicamentoId){
    $this->medicamentoId=$medicamentoId;
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

}
