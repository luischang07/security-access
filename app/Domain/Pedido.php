<?php


namespace App\Domain;
use Illuminate\Support\Collection;
class Pedido{

    private $cedulaProfesional;
    private $fecha_pedido,$fecha_recoleccion,$fecha_entrega;
    private $estatus;
    private $lineas_pedido;
    private $paciente_id,$sucursal_id,$cadena_id;

    private function __construct() {
        $this->createColeccionLineas();
    }

    public static function createPedido($paciente_id){

        $instancia = new self();

        $instancia->paciente_id=$paciente_id;

        return $instancia;
    }

    private function createColeccionLineas(){
        $this->lineas_pedido=collect();
    }
    public function asociarSucursalAPedido($cadena_id,$sucursal_id){
        $this->sucursal_id=$sucursal_id;
        $this->cadena_id=$cadena_id;
    }
    public function agregarMedicamento($medId,$cantidad, $medicamento){
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

        $linea_pedido=new LineaPedido($medId,$cantidad, $medicamento);
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

    public function getLineasPedido(){
        return $this->lineas_pedido->get(0);
    }

        public function getLineasPedidos(){
        return $this->lineas_pedido;
    }

    public function getSucursalSeleccionada(){
        return $this->sucursal_id;
    }

    public function getCadenaSeleccionada(){
        return $this->cadena_id;
    }
    
    public function crearDetalleLineaPedido($precio_unitario,$cantidadSurtida,$sucursal,$medicamento_id){

        $linea = $this->lineas_pedido->firstWhere('medicamento_id', $medicamento_id);


        if ($linea) {
            $linea->crearDetalleLineaPedido($precio_unitario,$cantidadSurtida,$sucursal);

        }

        
        $this->lineas_pedido = $this->lineas_pedido->map(function ($item) use ($medicamento_id,$precio_unitario,$cantidadSurtida,$sucursal) {
            if ($item->getMedicamentoId() === $medicamento_id) {
                $item->crearDetalleLineaPedido($precio_unitario,$cantidadSurtida,$sucursal);// se reemplaza
            }
            return $item;
        });
        
        info( "cambio? ",[$linea = $this->lineas_pedido->firstWhere('medicamento_id', $medicamento_id)]);
    }
    public function toArray(){
        return [
            'cedulaProfesional' => $this->cedulaProfesional,
            'fecha_pedido' => $this->fecha_pedido,
            'fecha_recoleccion' => $this->fecha_recoleccion,
            'fecha_entrega' => $this->fecha_entrega,
            'estatus' => $this->estatus,
            'lineas_pedido' => $this->lineas_pedido->map(function (LineaPedido $ldp) {
                return [
                    'medicamento_id' => $ldp->getMedicamentoId(),
                    'cantidad'       => $ldp->getCantidad(),
                ];
            })->values()->toArray(),
            'paciente_id' => $this->paciente_id,
            'sucursal_id' => $this->sucursal_id,
            'cadena_id' => $this->cadena_id
        ];
    }

    public function toSessionArray(){
        return $this->toArray();
    }

    
}
