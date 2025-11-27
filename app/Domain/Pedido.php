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
    }

    public static function createPedido($paciente_id){

        $instancia = new self();

        $instancia->paciente_id=$paciente_id;
        $instancia->createColeccionLineas();

        return $instancia;
    }

    public static function createPedidoFromSession($datosPedido){
        $instancia = new self();
        $instancia->cedulaProfesional=$datosPedido['cedulaProfesional'];
        $instancia->fecha_pedido=$datosPedido['fecha_pedido'];
        $instancia->fecha_recoleccion=$datosPedido['fecha_recoleccion'];
        $instancia->fecha_entrega=$datosPedido['fecha_entrega'];
        $instancia->estatus=$datosPedido['estatus'];
        $instancia->lineas_pedido=$datosPedido['lineas_pedido'];
        $instancia->paciente_id=$datosPedido['paciente_id'];
        $instancia->sucursal_id=$datosPedido['sucursal_id'];
        $instancia->cadena_id=$datosPedido['cadena_id'];
        return $instancia;
    }

    private function createColeccionLineas(){
        $this->lineas_pedido=collect();
    }
    public function asociarSucursalAPedido($cadena_id,$sucursal_id){
        $this->sucursal_id=$sucursal_id;
        $this->cadena_id=$cadena_id;
    }
    public function agregarMedicamento($medId,$cantidad,$medicamento){
        info('agregarMedicamento en Pedido', [$medId, $cantidad, $medicamento]);
        $linea_pedido=new LineaPedido($medId,$cantidad,$medicamento);
        $this->lineas_pedido->push($linea_pedido);
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
            'lineas_pedido' => $this->lineas_pedido,
            'paciente_id' => $this->paciente_id,
            'sucursal_id' => $this->sucursal_id,
            'cadena_id' => $this->cadena_id
        ];
    }

    
}
