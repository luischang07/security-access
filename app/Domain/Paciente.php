<?php

namespace App\Domain;

use Illuminate\Support\Collection;
use App\Models\User as UserModel;
use App\Models\Paciente as PacienteModel;
use App\Domain\User\UserEntity;
use App\Domain\Notificacion;

class Paciente
{
  private UserEntity $user;
  private float $montoPenalizacion = 0.0;

  /** @var Collection|Notificacion[] */
  private Collection $notificaciones;

  public function __construct(PacienteModel $paciente, UserModel $user, ?iterable $notificaciones = null)
  {
    $this->user = new UserEntity($user);
    $this->montoPenalizacion = (float) $paciente->monto_penalizacion;
    $this->notificaciones = collect();
    if ($notificaciones) {
      foreach ($notificaciones as $notificacion) {
        $this->notificaciones->push(new Notificacion($notificacion));
      }
    }
  }

  public function getUser(): UserEntity
  {
    return $this->user;
  }
  public function getMontoPenalizacion(): float
  {
    return $this->montoPenalizacion;
  }
  public function setMontoPenalizacion(float $monto): void
  {
    $this->montoPenalizacion += $monto;
  }
  public function reducirPenalizacion(float $monto): void
  {
    $this->montoPenalizacion -= $monto;
    if ($this->montoPenalizacion < 0.0) {
      $this->montoPenalizacion = 0.0;
    }
  }
  public function agregarNotificacion(Notificacion $notificacion): void
  {
    $this->notificaciones->push($notificacion);
  }

  public function getNotificaciones(): Collection
  {
    return $this->notificaciones;
  }
}
