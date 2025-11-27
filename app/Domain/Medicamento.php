<?php


namespace App\Domain;

class Medicamento{


    private $id,$nombre,$descripcion,$unidad_Medida,$unidades;

    public function __construct($id,$nombre,$descripcion,$unidad_Medida,$unidades){
        $this->id=$id;
        $this->nombre=$nombre;
        $this->descripcion=$descripcion;
        $this->unidad_Medida=$unidad_Medida;
        $this->unidades=$unidades;
    }

    public function getNombre(){
        return $this->nombre;
    }
    public function getDescripcion(){
        return $this->descripcion;
    }
    public function getUnidadMedida(){
        return $this->unidad_Medida;
    }
    public function getUnidades(){
        return $this->unidades;
    }
}