<?php

namespace App\Services\Modelos;
use App\Repositories\BaseDatos;

class MedicamentoService
{
  private BaseDatos $dataBase;

  public function __construct(){
      $this->dataBase=new BaseDatos();
  }

  public function obtenerMedicamentosPorNombre(string $nombre){
      return $this->dataBase->buscarMedicamentosPorNombre($nombre);
  }
}