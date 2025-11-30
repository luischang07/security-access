<?php

namespace App\Services\Modelos;
use App\Repositories\BaseDatos;
use App\Domain\Paciente;

class PacienteService{
    private $dataBase;
    private Paciente $paciente;

    public function __construct(){
        $this->dataBase= new BaseDatos();
    }

    public function getMontoPenalizacion($paciente_id){
        $this->paciente = $this->dataBase->obtenerPaciente($paciente_id);
        return $this->paciente->getMontoPenalizacion();
    }
    
    public function setMontoPenalizacion($paciente,$monto){
        $paciente->setMontoPenalizacion($monto);
    }

    public function getPedidoPorPaciente($user_id){
        $pedidos=$this->dataBase->getPedidos($user_id);
        return $pedidos;
    }
    public function getPedidosActivos($pedidos){
        $contador=0;
        foreach($pedidos as $pedido){
            if($pedido->getEstatus()=="confirmado"){
                $contador++;
            }
        }
        return $contador;
    }
}