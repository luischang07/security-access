<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

use App\Domain\Pedido;
use App\Domain\User\UserEntity;
use App\Mail\OrderReadyForPickupMail;
use App\Models\Notificacion;
use App\Models\Pedido as PedidoModel;
use App\Services\Modelos\PedidoService;
use App\Repositories\BaseDatos;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendOrderReadyNotification implements ShouldQueue
{
  use Queueable;

  public string $folio;
  public int $userId;

  /**
   * Create a new job instance.
   */
  public function __construct(string $folio, int $userId)
  {
    $this->folio = $folio;
    $this->userId = $userId;
  }

  /**
   * Execute the job.
   */
  public function handle(PedidoService $pedidoService, BaseDatos $baseDatos): void
  {
    // 1. Obtener el pedido actualizado
    $pedido = $pedidoService->getPedidoPorFolio($this->folio);

    if (!$pedido) {
      Log::warning("SendOrderReadyNotification: Pedido {$this->folio} no encontrado.");
      return;
    }

    // 2. CRÍTICO: Verificar si el estatus sigue siendo 'surtido'
    // Si el empleado hizo "Undo", el estatus habrá cambiado a 'confirmado' (o cualquier otro)
    if (strtolower($pedido->getEstatus()) !== PedidoModel::ESTATUS_SURTIDO) {
      Log::info("SendOrderReadyNotification: Pedido {$this->folio} ya no está en estatus 'surtido'. Cancelando envío de correo.");
      return;
    }

    // 3. Obtener usuario (paciente)
    $paciente = $baseDatos->getPaciente($this->userId);
    if (!$paciente) {
      Log::warning("SendOrderReadyNotification: Paciente {$this->userId} no encontrado.");
      return;
    }
    $user = $paciente->getUser();

    // 4. Enviar correo
    try {
      Mail::to($user->getCorreo())->send(new OrderReadyForPickupMail($pedido, $user));
      Log::info("SendOrderReadyNotification: Correo enviado a {$user->getCorreo()} para pedido {$this->folio}.");
    } catch (\Exception $e) {
      Log::error("SendOrderReadyNotification: Error enviando correo: " . $e->getMessage());
    }

    // 5. Crear notificación interna (Moviendo la lógica aquí para sincronizar)
    try {
      $mensaje = "Su pedido {$pedido->getfolio()} está listo para ser recogido. Tienes 48 horas para recogerlo en la sucursal {$pedido->getSucursal()->getNombre()}.";
      $notificacion = Notificacion::create([
        'user_id' => $this->userId,
        'folio_pedido' => $this->folio,
        'mensaje' => $mensaje,
        'fecha_hora' => now(),
        'leida' => false,
      ]);
      Log::info("SendOrderReadyNotification: Notificación interna creada para pedido {$this->folio}.");
    } catch (\Exception $e) {
      Log::error("SendOrderReadyNotification: Error creando notificación interna: " . $e->getMessage());
    }
  }
}
