<?php


namespace App\Domain;

class Medicamento
{
  private int $id;
  private string $nombre;
  private ?string $descripcion;
  private ?string $unidadDeMedida;
  private ?int $unidades;

  public function __construct(
    int $id,
    string $nombre,
    ?string $descripcion,
    ?string $unidadDeMedida,
    ?int $unidades
  ) {
    $this->id = $id;
    $this->nombre = $nombre;
    $this->descripcion = $descripcion;
    $this->unidadDeMedida = $unidadDeMedida;
    $this->unidades = $unidades;
  }

  public function getId(): int
  {
    return $this->id;
  }

  public function getNombre(): string
  {
    return $this->nombre;
  }

  public function getDescripcion(): ?string
  {
    return $this->descripcion;
  }

  public function getUnidadMedida(): ?string
  {
    return $this->unidadDeMedida;
  }

  public function getUnidades(): ?int
  {
    return $this->unidades;
  }
}
