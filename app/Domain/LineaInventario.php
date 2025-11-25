<?php


namespace App\Domain;

class LineaInventario {
    private $cadena_id,$sucursal_id;
    private $medicamento_id;
    private $stock_disponible;
    private $precio_unitario;

    public function __construct($cadena_id,$sucursal_id,$medicamento_id,$stock_disponible,$precio_unitario){
        $this->cadena_id=$cadena_id;
        $this->sucursal_id=$sucursal_id;
        $this->medicamento_id=$medicamento_id;
        $this->stock_disponible=$stock_disponible;
        $this->precio_unitario=$precio_unitario;
    }

    public function getMedicamentoId(){
    return $this->medicamento_id;
    }
    public function getStockDisponible(){
    return $this->stock_disponible;
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
    public function getPrecioUnitario(){
        return $this->precio_unitario;
    }
}
