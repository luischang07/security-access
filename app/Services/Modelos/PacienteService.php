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
        $this->paciente = $this->dataBase->obtenerPedidoPorId($paciente_id);
        return $this->paciente->getMontoPenalizacion();
    }
    
    public function setMontoPenalizacion($paciente,$monto){
        $paciente->setMontoPenalizacion($monto);
    }



}