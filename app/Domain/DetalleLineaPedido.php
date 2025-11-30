<?php


namespace App\Domain;

class DetalleLineaPedido
{
    private $precio;
    private $cantidadSurtida;
    private $sucsel;

    private $medicamento_id;

    public function __construct($precio, $cantidadSurtida, $sucsel, $medicamento_id)
    {
        $this->precio = $precio;
        $this->cantidadSurtida = $cantidadSurtida;
        $this->sucsel = $sucsel;
        $this->medicamento_id = $medicamento_id;
    }

    public function calcularSubtotalDetalle()
    {
        return $this->precio * $this->cantidadSurtida;
    }

    public function getCantidadSurtida()
    {
        return $this->cantidadSurtida;
    }
    public function getSucursal()
    {
        return $this->sucsel;
    }
    public function getPrecio()
    {
        return $this->precio;
    }
    public function getMedicamento_id()
    {
        return $this->medicamento_id;
    }

    // Alias camelCase for callers that expect getMedicamentoId()
    public function getMedicamentoId()
    {
        return $this->medicamento_id;
    }
}
