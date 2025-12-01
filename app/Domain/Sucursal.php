<?php

namespace App\Domain;

use Illuminate\Support\Collection;

class Sucursal
{
  private string $cadenaId;
  private string $sucursalId;
  private string $nombre;

  private string $calle;
  private string $numeroExterior;
  private ?string $numeroInterior;
  private ?string $ciudad;
  private string $colonia;

  private float $latitud;
  private float $longitud;

  /** @var array|Collection LineaInventario[] */
  private $inventario;
  private $notificaciones;

  public function __construct(
    string $cadenaId,
    string $sucursalId,
    string $nombre,
    string $calle,
    string $numeroExterior,
    ?string $numeroInterior,
    ?string $ciudad,
    string $colonia,
    float $latitud,
    float $longitud,
  ) {
    $this->cadenaId = $cadenaId;
    $this->sucursalId = $sucursalId;
    $this->nombre = $nombre;
    $this->calle = $calle;
    $this->numeroExterior = $numeroExterior;
    $this->numeroInterior = $numeroInterior;
    $this->ciudad = $ciudad;
    $this->colonia = $colonia;
    $this->latitud = $latitud;
    $this->longitud = $longitud;
    $this->inventario = array();
    $this->notificaciones = collect();
  }
  public static function crear(object $data): self
  {
    return new self(
      $data->cadena_id,
      $data->sucursal_id,
      $data->nombre,
      $data->calle,
      $data->numero_ext,
      $data->numero_int,
      $data->ciudad,
      $data->colonia,
      $data->latitud,
      $data->longitud,
    );
  }

  //Getters
  public function getCadenaId(): string
  {
    return $this->cadenaId;
  }

  public function getSucursalId(): string
  {
    return $this->sucursalId;
  }

  public function getNombre(): string
  {
    return $this->nombre;
  }

  public function getCalle(): string
  {
    return $this->calle;
  }

  public function getNumeroExterior(): string
  {
    return $this->numeroExterior;
  }

  public function getNumeroInterior(): ?string
  {
    return $this->numeroInterior;
  }

  public function getCiudad(): ?string
  {
    return $this->ciudad;
  }

  public function getColonia(): string
  {
    return $this->colonia;
  }

  public function getLatitud(): float
  {
    return $this->latitud;
  }

  public function getLongitud(): float
  {
    return $this->longitud;
  }

  public function agregarNotificacion($notificacion)
  {
    $this->notificaciones->push($notificacion);
  }

  public function getDireccion()
  {
    $direccion = $this->calle . ' ' . $this->numeroExterior;
    if (!empty($this->numeroInterior)) {
      $direccion .= ', Int. ' . $this->numeroInterior;
    }
    $direccion .= ', ' . $this->colonia . ', ' . $this->ciudad;
    return $direccion;
  }

  //Setters
  public function setCadenaId(string $cadenaId): void
  {
    $this->cadenaId = $cadenaId;
  }

  public function setSucursalId(string $sucursalId): void
  {
    $this->sucursalId = $sucursalId;
  }

  public function setNombre(string $nombre): void
  {
    $this->nombre = $nombre;
  }

  public function setCalle(string $calle): void
  {
    $this->calle = $calle;
  }

  public function setNumeroExterior(string $numeroExterior): void
  {
    $this->numeroExterior = $numeroExterior;
  }

  public function setNumeroInterior(?string $numeroInterior): void
  {
    $this->numeroInterior = $numeroInterior;
  }

  public function setCiudad(?string $ciudad): void
  {
    $this->ciudad = $ciudad;
  }

  public function setColonia(string $colonia): void
  {
    $this->colonia = $colonia;
  }

  public function setLatitud(float $latitud): void
  {
    $this->latitud = $latitud;
  }

  public function setLongitud(float $longitud): void
  {
    $this->longitud = $longitud;
  }
}
