<?php

namespace App\Domain;

use Illuminate\Support\Collection;
use App\Models\Notificacion as NotificacionModel;
use Carbon\Carbon;

class Notificacion
{
  private string $mensaje;
  private Carbon $fechaEnvio;
  private bool $leida;

  public function __construct(NotificacionModel $notificacion)
  {
    $this->mensaje = $notificacion->mensaje;
    $this->fechaEnvio = new Carbon($notificacion->fecha_hora);
    $this->leida = (bool) $notificacion->leida;
  }

  public static function crear(string $mensaje, Carbon $fechaEnvio, bool $leida = false): self
  {
    $notificacion = new NotificacionModel([
      'mensaje' => $mensaje,
      'fecha_hora' => $fechaEnvio->format('Y-m-d H:i:s'),
      'leida' => $leida
    ]);

    return new self($notificacion);
  }

  public function marcarComoLeida(): void
  {
    $this->leida = true;
  }

  public function esLeida(): bool
  {
    return $this->leida;
  }

  public function getMensaje(): string
  {
    return $this->mensaje;
  }

  public function getFechaEnvio(): Carbon
  {
    return $this->fechaEnvio;
  }
}
