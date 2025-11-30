<?php

namespace App\Domain;
use Illuminate\Support\Collection;
use App\Models\Notificacion as NotificacionModel;


class Notificacion
{
  private $mensaje;
  private $fecha_envio;
  private $leida;

  public function __construct(NotificacionModel $noficacion)
  {
    $this->mensaje = $noficacion->mensaje;
    $this->fecha_envio = $noficacion->fecha_hora;
    $this->leida = $noficacion->leida;
  }

  public static function crear($mensaje, $fecha_hora, $leida = false)
  {
    $notificacion = new NotificacionModel([
      'mensaje' => $mensaje,
      'fecha_hora' => $fecha_hora,
      'leida' => $leida
    ]);

    return new self($notificacion);
  }

  public function marcarComoLeida()
  {
    $this->leida = true;
  }

  public function esLeida()
  {
    return $this->leida;
  }

  public function getMensaje()
  {
    return $this->mensaje;
  }

  public function getFechaEnvio()
  {
    return $this->fecha_envio;
  }
}