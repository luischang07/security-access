<?php


namespace App\Domain;
use Illuminate\Support\Collection;
use App\Domain\Sucursales;
class Pedido
{

    private $cedulaProfesional;
    private $fecha_pedido, $fecha_recoleccion, $fecha_entrega;
    private $estatus;
    private $lineas_pedido;
    private $paciente_id;
    private $sucursal;

    private function __construct()
    {
        $this->createColeccionLineas();
    }

    public static function createPedido($paciente_id)
    {

        $instancia = new self();

        $instancia->paciente_id = $paciente_id;

        return $instancia;
    }

    private function createColeccionLineas()
    {
        $this->lineas_pedido = collect();
    }
    public function asociarSucursalAPedido($sucursal)
    {
        $this->sucursal = $sucursal;
    }
    public function agregarMedicamento($medId, $cantidad, $medicamento)
    {
        if (!$medId) {
            return;
        }
        if (!$this->lineas_pedido instanceof Collection) {
            $this->createColeccionLineas();
        }

        $existing = $this->lineas_pedido->first(function ($ldp) use ($medId) {
            return $ldp->getMedicamentoId() === (int) $medId;
        });

        if ($existing) {
            $existing->setCantidad((int) $cantidad);
            return;
        }

        $linea_pedido = new LineaPedido($medId, $cantidad, $medicamento);
        $this->lineas_pedido->push($linea_pedido);
    }

    public function eliminarMedicamento($medId): void
    {
        if (!$this->lineas_pedido instanceof Collection) {
            return;
        }

        $this->lineas_pedido = $this->lineas_pedido
            ->filter(function (LineaPedido $ldp) use ($medId) {
                return $ldp->getMedicamentoId() !== (int) $medId;
            })
            ->values();
    }
    public function eliminarDetalle($dlp): void
    {
        if (!$this->lineas_pedido instanceof Collection) {
            return;
        }

        // Actualiza la colección removiendo la línea cuyo medicamento coincide
        $this->lineas_pedido = $this->lineas_pedido
            ->first(function (LineaPedido $ldp) use ($dlp) {
                return $ldp->getMedicamentoId() !== (int) $dlp->getMedicamento_id();
            })
            ->values();
    }

    /**
     * Devuelve la LineaPedido para el medicamento dado o null si no existe.
     */
    public function getLineaPedido($medId)
    {
        if (!$this->lineas_pedido instanceof Collection) {
            return null;
        }

        return $this->lineas_pedido->first(function ($ldp) use ($medId) {
            return $ldp->getMedicamentoId() === (int) $medId;
        });
    }

    public function getLineasPedido()
    {
        return $this->lineas_pedido->get(0);
    }

    public function getLineasPedidos()
    {
        return $this->lineas_pedido;
    }

    public function obtenerDetallesLineas()
    {
        $detalles = collect();
        foreach ($this->lineas_pedido as $linea) {
            $detalles->push($linea->getDetalleLineaPedido());
        }
        return $detalles;
    }

    public function getSucursal()
    {
        return $this->sucursal;
    }

    public function crearDetalleLineaPedido($precio_unitario, $cantidadSurtida, $sucursal, $medicamento_id)
    {

        $linea = $this->lineas_pedido->firstWhere('medicamento_id', $medicamento_id);


        if ($linea) {
            $linea->crearDetalleLineaPedido($precio_unitario, $cantidadSurtida, $sucursal, $medicamento_id);

        }


        $this->lineas_pedido = $this->lineas_pedido->map(function ($item) use ($medicamento_id, $precio_unitario, $cantidadSurtida, $sucursal) {
            if ($item->getMedicamentoId() === $medicamento_id) {
                $item->crearDetalleLineaPedido($precio_unitario, $cantidadSurtida, $sucursal, $medicamento_id);// se reemplaza
            }
            return $item;
        });

    }
}
