<?php


namespace App\Domain;
use Illuminate\Support\Collection;
use App\Domain\Sucursal;
use App\Domain\LineaPedido;
use App\Domain\Medicamento;
use Carbon\Carbon;

class Pedido
{
    private $folio;
    private $cedulaProfesional;
    private $fecha_pedido, $fecha_recoleccion;
    private $estatus;
    private $lineas_pedido;
    private $paciente_id;
    private $sucursal;
    private $costo_Total;

    private $ruta;

    private function __construct()
    {
        $this->createColeccionLineas();
        $this->ruta = collect();
    }

    public static function createPedido($paciente_id)
    {
        $instancia = new self();

        $instancia->paciente_id = $paciente_id;

        return $instancia;
    }

    public function getTotal()
    {
        $total = 0;
        foreach ($this->lineas_pedido as $linea) {
            $detalles = $linea->getDetalles();
            foreach ($detalles as $dlp) {
                $total += $dlp->getPrecio();
            }
        }
        $this->costo_Total = $total;
        return $this->costo_Total;
    }

    private function createColeccionLineas()
    {
        $this->lineas_pedido = collect();
    }
    public function asociarSucursalAPedido($sucursal)
    {
        $this->sucursal = $sucursal;
    }

    public function añadirARuta($sucursal)
    {
        //Si ya existe la sucursal dentro de la ruta, no agregarla de nuevo
        if (
            !$this->ruta->contains(function ($suc) use ($sucursal) {
                return $suc->getCadenaId() === $sucursal->getCadenaId() &&
                    $suc->getSucursalId() === $sucursal->getSucursalId();
            })
        ) {
            $this->ruta->push($sucursal);
        }
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

        $linea_pedido = $this->lineas_pedido
            ->filter(function (LineaPedido $ldp) use ($dlp) {
                return $ldp->getMedicamentoId() === (int) $dlp->getMedicamento_id();
            })
            ->values();
        $linea_pedido->get(0)->eliminarDetalle($dlp);
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

    public function obtenerDetallesLineas()
    {
        $detalles = collect();
        foreach ($this->lineas_pedido as $linea) {
            $detalles->push($linea->getDetalleLineaPedido());
        }
        return $detalles;
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

    public function calcularTotales()
    {
        $acumulado = 0;
        foreach ($this->lineas_pedido as $linea) {
            $acumulado += $linea->calcularSubtotal();
        }
        $this->costo_Total = $acumulado;
    }

    public function asignarFechaPedido()
    {
        $this->fecha_pedido = Carbon::now();
    }

    public function asignarFechaRecoleccion()
    {
        $this->fecha_recoleccion = $this->fecha_pedido->copy()->addDay();
    }

    public function getLineasPedido()
    {
        return $this->lineas_pedido->get(0);
    }

    public function getLineasPedidos()
    {
        return $this->lineas_pedido;
    }

    public function getSucursal()
    {
        return $this->sucursal;
    }

    public function setCedulaProfesional($cedula)
    {
        $this->cedulaProfesional = $cedula;
    }

    public function getCedulaProfesional()
    {
        return $this->cedulaProfesional;
    }

    public function getFechaRecoleccion()
    {
        return $this->fecha_recoleccion;
    }

    public function getEstatus()
    {
        return $this->estatus;
    }

    public function setEstatus()
    {
        $this->estatus = "confirmado";
    }

    public function cambiarEstatus($nuevoEstatus)
    {
        $this->estatus = $nuevoEstatus;
    }

    public function getCostoTotal()
    {
        return $this->costo_Total;
    }

    public function getFolio()
    {
        return $this->folio;
    }

    public function asignarFolio($folio)
    {
        $this->folio = $folio;
    }

    public function getPacienteId()
    {
        return $this->paciente_id;
    }
    public function getRuta()
    {
        return $this->ruta;
    }
    public function getFechaPedido()
    {
        return $this->fecha_pedido;
    }
    //Crear pedido desde modelo Eloquent
    public static function crear($pedidoModel)
    {
        $pedido = new self();
        $pedido->folio = $pedidoModel->folio_pedido;
        $pedido->paciente_id = $pedidoModel->paciente_id;
        $pedido->cedulaProfesional = $pedidoModel->cedula_profesional;
        $pedido->fecha_pedido = $pedidoModel->fecha_pedido;
        $pedido->fecha_recoleccion = $pedidoModel->fecha_recoleccion;
        $pedido->estatus = $pedidoModel->estatus;
        $pedido->costo_Total = $pedidoModel->costo_total;

        //crear sucursal
        $sucursal = Sucursal::crear($pedidoModel->sucursal);
        $pedido->asociarSucursalAPedido($sucursal);

        $lineasPedidoCollection = collect();
        foreach ($pedidoModel->lineasPedidos as $lineaModel) {
            $medicamento = new Medicamento($lineaModel->medicamento->medicamento_id, $lineaModel->medicamento->nombre, $lineaModel->medicamento->descripcion, $lineaModel->medicamento->unidad_medida, $lineaModel->medicamento->unidades);
            $lineaPedido = $lineaPedido = new LineaPedido($lineaModel->medicamento_id, $lineaModel->cantidad, $medicamento);
            $lineasPedidoCollection->push($lineaPedido);
            //Crear crearDetalleLineaPedido
            foreach ($lineaModel->detalles as $detalleModel) {
                $sucursalDetalle = Sucursal::crear($detalleModel->sucursal);
                $lineaPedido->crearDetalleLineaPedido($detalleModel->precio_unitario, $detalleModel->cantidad_surtida, $sucursalDetalle, $detalleModel->medicamento_id);
            }
        }
        $pedido->lineas_pedido = $lineasPedidoCollection;

        return $pedido;
    }
}
