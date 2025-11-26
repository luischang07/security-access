<?php

namespace App\Services\Modelos;
use App\Repositories\BaseDatos;
use App\Domain\Pedido;
use App\Domain\Sucursal;
use Illuminate\Support\Facades\Session;

class PedidoService
{

    private Pedido $pedido;
    private Sucursal $sucursal;
    private BaseDatos $dataBase;

    public function __construct(){
        $this->dataBase=new BaseDatos();
    }
    
    public function nuevoPedido($paciente_id){
        $this->pedido=Pedido::createPedido($paciente_id);

        Session::put('pedido_temporal', $this->pedido->toArray());

        return $this->pedido;
    }


    public function asociarSucursalAPedido($sucursal_id,$cadena_id){

        $datosPedido = Session::get('pedido_temporal');

        if (!$datosPedido) {
            throw new \RuntimeException('No hay pedido en captura en la sesión.');
        }

        $pedido = Pedido::createPedidoFromSession($datosPedido);

        $pedido->asociarSucursalAPedido($cadena_id,$sucursal_id);

        Session::put('pedido_temporal', $pedido->toArray());

    }

    public function agregarMedicamento($medId,$cantidad){

        $datosPedido = Session::get('pedido_temporal');

        if (!$datosPedido) {
            throw new \RuntimeException('No hay pedido en captura para agregar medicamento.');
        }

        $pedido = Pedido::createPedidoFromSession($datosPedido);
        
        $pedido->agregarMedicamento($medId,$cantidad);
        info("Pedido después de agregar medicamento: " . json_encode($pedido->toArray()));
        Session::put('pedido_temporal', $pedido->toArray());
    }

    public function eliminarMedicamento($medId)
    {
        $datosPedido = Session::get('pedido_temporal');

        if (!$datosPedido) {
            throw new \RuntimeException('No hay pedido en captura para eliminar un medicamento.');
        }

        $pedido = Pedido::createPedidoFromSession($datosPedido);

        $pedido->eliminarMedicamento($medId);

        Session::put('pedido_temporal', $pedido->toSessionArray());
    }

    public function obtenerSucursal($cadena_id, $sucursal_id){
        $this->sucursal=$this->dataBase->obtenerSucursal($cadena_id,$sucursal_id);
        return $this->sucursal;
    }

    public function obtenerLineasPedidoActuales(): array
    {
        $datosPedido = Session::get('pedido_temporal');

        if (!$datosPedido || empty($datosPedido['lineas_pedido'])) {
            return [];
        }

        $lineas = collect($datosPedido['lineas_pedido']);
        $medicamentos = $this->dataBase->obtenerMedicamentosPorIds($lineas->pluck('medicamento_id')->toArray());

        return $lineas->map(function ($linea) use ($medicamentos) {
            $med = $medicamentos->get($linea['medicamento_id']);
            return [
                'id' => $linea['medicamento_id'],
                'name' => $med->nombre ?? 'Medicamento ' . $linea['medicamento_id'],
                'quantity' => (int) $linea['cantidad'],
            ];
        })->values()->all();
    }
}
