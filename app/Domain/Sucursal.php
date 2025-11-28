<?php

namespace App\Domain;

class Sucursal
{

  private $cadena_id, $sucursal_id, $nombre;
  private $calle, $numero_exterior, $numero_interior, $ciudad, $colonia;
  private $latitud, $longitud, $distancia;
  private $inventario;

  public function __construct($cadena_id, $sucursal_id, $nombre, $calle, $numero_exterior, $numero_interior, $ciudad, $colonia, $latitud, $longitud, $distancia)
  {
    $this->cadena_id = $cadena_id;
    $this->sucursal_id = $sucursal_id;
    $this->nombre = $nombre;
    $this->calle = $calle;
    $this->numero_exterior = $numero_exterior;
    $this->numero_interior = $numero_interior;
    $this->ciudad = $ciudad;
    $this->colonia = $colonia;
    $this->latitud = $latitud;
    $this->longitud = $longitud;
    $this->distancia = $distancia;
    $this->inventario = array();
  }
  public static function crear($data)
  {
    return new self($data->cadena_id, $data->sucursal_id, $data->nombre, $data->calle, $data->numero_ext, $data->numero_int, $data->ciudad, $data->colonia, $data->latitud, $data->longitud, $data->distancia);
  }

  //Getters y Setters
  public function getCadenaId()
  {
    return $this->cadena_id;
  }
  public function getSucursalId()
  {
    return $this->sucursal_id;
  }
  public function getNombre()
  {
    return $this->nombre;
  }
  public function getCalle()
  {
    return $this->calle;
  }
  public function getNumeroExterior()
  {
    return $this->numero_exterior;
  }
  public function getNumeroInterior()
  {
    return $this->numero_interior;
  }
  public function getCiudad()
  {
    return $this->ciudad;
  }
  public function getColonia()
  {
    return $this->colonia;
  }
  public function getLatitud()
  {
    return $this->latitud;
  }
  public function getLongitud()
  {
    return $this->longitud;
  }
  public function setCadenaId($cadena_id)
  {
    $this->cadena_id = $cadena_id;
  }
  public function setSucursalId($sucursal_id)
  {
    $this->sucursal_id = $sucursal_id;
  }
  public function setNombre($nombre)
  {
    $this->nombre = $nombre;
  }
  public function setCalle($calle)
  {
    $this->calle = $calle;
  }
  public function setNumeroExterior($numero_exterior)
  {
    $this->numero_exterior = $numero_exterior;
  }
  public function setNumeroInterior($numero_interior)
  {
    $this->numero_interior = $numero_interior;
  }
  public function setCiudad($ciudad)
  {
    $this->ciudad = $ciudad;
  }
  public function setColonia($colonia)
  {
    $this->colonia = $colonia;
  }
  public function setLatitud($latitud)
  {
    $this->latitud = $latitud;
  }
  public function setLongitud($longitud)
  {
    $this->longitud = $longitud;
  }

  public function getDireccion()
  {
    $direccion = $this->calle . ' ' . $this->numero_exterior;
    if (!empty($this->numero_interior)) {
      $direccion .= ', Int. ' . $this->numero_interior;
    }
    $direccion .= ', ' . $this->colonia . ', ' . $this->ciudad;
    return $direccion;
  }

}
