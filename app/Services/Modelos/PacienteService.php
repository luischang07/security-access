<?php

namespace App\Services\Modelos;

use App\Repositories\BaseDatos;
use App\Domain\Paciente;
use App\Domain\Pedido;
use App\Models\Pedido as PedidoModel;
use Illuminate\Support\Collection;

class PacienteService
{
  private BaseDatos $dataBase;
  private Paciente $paciente;

  public function __construct()
  {
    $this->dataBase = new BaseDatos();
  }

  public function getMontoPenalizacion(int $paciente_id): float
  {
    $this->paciente = $this->dataBase->getPaciente($paciente_id);
    return $this->paciente->getMontoPenalizacion();
  }

  public function sumarMontoPenalizacion(Paciente $paciente, float $monto)
  {
    $paciente->sumarMontoPenalizacion($monto);
  }

  public function getPedidosPorPaciente(int $user_id): Collection
  {
    return $this->dataBase->getPedidos($user_id);
  }

  public function getPedidosActivos(Collection $pedidos): int
  {
    $contador = 0;
    foreach ($pedidos as $pedido) {
      /** @var Pedido $pedido */
      if ($pedido->getEstatus() == PedidoModel::ESTATUS_CONFIRMADO) {
        $contador++;
      }
    }
    return $contador;
  }
}
