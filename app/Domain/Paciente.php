<?php

namespace App\Domain;
use Illuminate\Support\Collection;
use App\Models\User as UserModel;
use App\Models\User as PacienteModel;
use App\Domain\User\UserEntity;
class Paciente
{
    private $user;
    private $monto_penalizacion = 0 ;

    public function __construct(PacienteModel $paciente, UserModel $user)
    {
        $this->user = new UserEntity($user); 
        $this->monto_penalizacion = $paciente->getMontoPenalizacion();
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
}