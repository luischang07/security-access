<?php

namespace App\Services\Modelos;
use App\Repositories\BaseDatos;

class CadenaService
{
  private BaseDatos $dataBase;

  public function __construct(){
      $this->dataBase=new BaseDatos();
  }

  public function obtenerTodasCadenas(){
      return $this->dataBase->obtenerCadenas();
  }
}