<?php

namespace App\Domain;
use Illuminate\Support\Collection;
use App\Models\User as UserModel;
use App\Models\Paciente as PacienteModel;
use App\Domain\User\UserEntity;
use App\Domain\Notificacion;
class Paciente
{
    private $user;
<<<<<<< HEAD
    private $monto_penalizacion=0;
    private $notificaciones;
=======
    private $notificaciones;
    private $monto_penalizacion = 0;
>>>>>>> arturo

    public function __construct(PacienteModel $paciente, UserModel $user, $notificaciones = null)
    {
        $this->user = new UserEntity($user);
        $this->monto_penalizacion = $paciente->monto_penalizacion;
        $this->notificaciones = collect();
        if ($notificaciones) {
            foreach ($notificaciones as $notificacion) {
                $this->notificaciones->push(new Notificacion($notificacion));
            }
        }
    }

    public function getUser()
    {
        return $this->user;
    }
    public function getMontoPenalizacion()
    {
        return $this->monto_penalizacion;
    }
    public function setMontoPenalizacion($monto)
    {
        $this->monto_penalizacion += $monto;
    }
    public function reducirPenalizacion($monto)
    {
        $this->monto_penalizacion -= $monto;
        if ($this->monto_penalizacion < 0) {
            $this->monto_penalizacion = 0;
        }
    }
    public function agregarNotificacion($notificacion)
    {
        $this->notificaciones->push($notificacion);
    }

    public function getNotificaciones()
    {
        return $this->notificaciones;
    }
}