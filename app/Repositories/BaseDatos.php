<?php

namespace App\Repositories;

use App\Models\Inventario;
use App\Models\CadenaFarmaceutica;
use App\Domain\LineaInventario;

use App\Models\DetalleLineaPedido;
use App\Models\RutaRecoleccion;

use App\Models\Medicamento;
use App\Domain\Medicamento as med;

use App\Models\Sucursal;
use App\Domain\Sucursal as DomainSucursal;

use App\Models\User;
use App\Models\Paciente;
use App\Domain\Paciente as DomainPaciente;

use App\Models\Pedido;
use App\Domain\Pedido as DomainPedido;

use App\Models\LineaPedido;
use App\Domain\LineaPedido as DomainLineaPedido;

use App\Domain\DetalleLineaPedido as DomainDetalleLineaPedido;

use App\Models\Notificacion;
use App\Domain\Notificacion as DomainNotificacion;
use App\Models\Empleado;

use App\Models\PedidoPenalizacion;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BaseDatos
{

  public function getTodasSucursales(): Collection
  {
    $sucursales = Sucursal::all();
    return $sucursales;
  }

  public function getSucursal($cadena_id, $sucursal_id): ?DomainSucursal
  {
    $sucursal = Sucursal::where('cadena_id', $cadena_id)->where('sucursal_id', $sucursal_id)->first();
    $sucursal = DomainSucursal::crear($sucursal);
    return $sucursal;
  }

  /**
   * This method needs to be executed within a database transaction.
   * All operations performed inside this method are atomic and will be committed or rolled back as a single unit.
   */
  public function getInventario($cadena_id, $sucursal_id, $medId): ?LineaInventario
  {
    $data = Inventario::where('cadena_id', $cadena_id)->where('sucursal_id', $sucursal_id)->where('medicamento_id', $medId)->lockForUpdate()->first();

    if (!$data) {
      return null;
    }

    return new LineaInventario($data->cadena_id, $data->sucursal_id, $data->medicamento_id, $data->stock_disponible, $data->precio_unitario);
  }

  public function getCadenas(): Collection
  {
    return CadenaFarmaceutica::select('cadena_id', 'nombre')->orderBy('nombre')->get();
  }

  public function getSucursalesPorCadena($cadena_id): Collection
  {
    return Sucursal::select([
      'cadena_id',
      'sucursal_id',
      'nombre',
      'calle',
      'numero_ext',
      'numero_int',
      'ciudad',
      'colonia',
      'contacto',
      'latitud',
      'longitud'
    ])->where('cadena_id', $cadena_id)->get();
  }

  public function getMedicamento($medId): ?med
  {
    $medicamento = Medicamento::where('id', $medId)->first();
    return new med($medicamento->id, $medicamento->nombre, $medicamento->descripcion, $medicamento->unidad_medida, $medicamento->unidades);
  }

  public function buscarMedicamentosPorNombre(string $nombre): Collection
  {
    return Medicamento::where('nombre', 'like', '%' . $nombre . '%')
      ->orderBy('nombre')
      ->limit(10)
      ->get(['id', 'nombre', 'unidad_medida', 'unidades']);
  }


  public function actualizarInventario($ldi)
  {
    Inventario::where('cadena_id', $ldi->getCadenaId())
      ->where('sucursal_id', $ldi->getSucursalId())
      ->where('medicamento_id', $ldi->getMedicamentoId())
      ->update(['stock_disponible' => $ldi->getStockDisponible()]);
  }

  public function actualizarInventarioCancelacion(LineaInventario $inventario)
  {
    Inventario::where('cadena_id', $inventario->getCadenaId())
      ->where('sucursal_id', $inventario->getSucursalId())
      ->where('medicamento_id', $inventario->getMedicamentoId())
      ->update(['stock_disponible' => $inventario->getStockDisponible()]);
  }

  public function getPaciente($paciente_id): ?DomainPaciente
  {
    $paciente = Paciente::where('user_id', $paciente_id)->first();
    $user = User::where('user_id', $paciente->user_id)->first();
    $notificaciones = Notificacion::where('user_id', $paciente_id)->get();
    return new DomainPaciente($paciente, $user, $notificaciones);
  }

  public function actualizarPaciente(DomainPaciente $paciente)
  {
    Paciente::where('user_id', $paciente->getUser()->getId())
      ->update(['monto_penalizacion' => $paciente->getMontoPenalizacion()]);
  }

  public function guardarNotificacion(DomainNotificacion $notificacion, int $user_id, string $folio_pedido)
  {
    Notificacion::create([
      'user_id' => $user_id,
      'folio_pedido' => $folio_pedido,
      'mensaje' => $notificacion->getMensaje(),
      'fecha_hora' => $notificacion->getFechaEnvio(),
      'leida' => $notificacion->esLeida(),
    ]);
  }

  public function guardarNotificacionSucursal($sucursal, $folio_pedido, $notificacion)
  {
    //Obtener los user_id de la sucursal
    $users_ids = Empleado::where('cadena_id', $sucursal->getCadenaId())
      ->where('sucursal_id', $sucursal->getSucursalId())
      ->pluck('user_id');
    foreach ($users_ids as $user_id) {
      Notificacion::create([
        'user_id' => $user_id,
        'folio_pedido' => $folio_pedido,
        'mensaje' => $notificacion->getMensaje(),
        'fecha_hora' => $notificacion->getFechaEnvio(),
        'leida' => $notificacion->esLeida(),
      ]);
    }
  }

  public function guardarPedido(DomainPedido $pedido): Pedido
  {
    $pedidoModel = Pedido::create([
      'paciente_id' => $pedido->getPacienteId(),
      'cadena_id' => $pedido->getSucursal()->getCadenaId(),
      'sucursal_id' => $pedido->getSucursal()->getSucursalId(),
      'cedula_profesional' => $pedido->getCedulaProfesional(),
      'fecha_pedido' => $pedido->getFechaPedido(),
      'fecha_recoleccion' => $pedido->getFechaRecoleccion(),
      'estatus' => $pedido->getEstatus(),
      'costo_total' => $pedido->getCostoTotal(),
      'route_geometry' => $pedido->getRouteGeometry(),
    ]);

    return $pedidoModel;
  }

  public function guardarCambioEstatusPedido(DomainPedido $pedido)
  {
    Pedido::where('folio_pedido', $pedido->getFolio())->update(['estatus' => $pedido->getEstatus()]);
  }

  public function guardarLineaPedido(DomainLineaPedido $ldp, $folio_pedido): LineaPedido
  {
    return LineaPedido::create([
      'folio_pedido' => $folio_pedido,
      'medicamento_id' => $ldp->getMedicamentoId(),
      'cantidad' => $ldp->getCantidad(),
    ]);
  }

  public function guardarDetalleLineaPedido(DomainDetalleLineaPedido $dlp, $folio_pedido, $id_lineapedido): DetalleLineaPedido
  {
    return DetalleLineaPedido::create([
      'folio_pedido' => $folio_pedido,
      'cadena_id' => $dlp->getSucursal()->getCadenaId(),
      'sucursal_id' => $dlp->getSucursal()->getSucursalId(),
      'medicamento_id' => $dlp->getMedicamentoId(),
      'precio_unitario' => $dlp->getPrecio(),
      'cantidad_surtida' => $dlp->getCantidadSurtida(),
    ]);
  }

  public function guardarRutaRecoleccion(array $data): RutaRecoleccion
  {
    return RutaRecoleccion::create([
      'folio_pedido' => $data['folio_pedido'],
      'cadena_id' => $data['cadena_id'],
      'sucursal_id' => $data['sucursal_id'],
      'orden_recoleccion' => $data['orden'],
    ]);
  }

  public function getPedidos($user_id): Collection
  {
    $pedidos = Pedido::where('paciente_id', $user_id)->with(["lineasPedidos.medicamento", "lineasPedidos.detalles"])->get();

    $pedidos = $pedidos->map(function ($pedido) {
      return DomainPedido::crear($pedido);
    });

    return $pedidos;
  }

  public function getPedidoByFolio($folio): ?DomainPedido
  {
    $pedido = Pedido::where('folio_pedido', $folio)->with(['lineasPedidos.medicamento', 'lineasPedidos.detalles'])->first();
    if (!$pedido) {
      return null;
    }
    $pedido->append('sucursal');

    return DomainPedido::crear($pedido);
  }

  public function getPedidosPorSucursal($cadena_id, $sucursal_id)
  {
    $pedidos = Pedido::query()
      ->where(function ($q) use ($cadena_id, $sucursal_id) {
        $q->where('cadena_id', $cadena_id)
          ->where('sucursal_id', $sucursal_id);
      })
      ->orWhereHas('lineasPedidos.detalles', function ($q) use ($cadena_id, $sucursal_id) {
        $q->where('cadena_id', $cadena_id)
          ->where('sucursal_id', $sucursal_id);
      })
      ->with(['penalizacion', 'lineasPedidos.detalles.sucursal'])
      ->orderBy('fecha_pedido', 'desc')
      ->get();

    $pedidos = $pedidos->map(function ($pedido) {
      return DomainPedido::crear($pedido);
    });

    return $pedidos;
  }

  public function iniciarTransaccion()
  {
    DB::beginTransaction();
  }

  public function commitTransaccion()
  {
    DB::commit();
  }

  public function cancelarTransaccion()
  {
    DB::rollBack();
  }

  public function guardarMontoPenalizacion($folio, $monto)
  {
    PedidoPenalizacion::create([
      'folio_pedido' => $folio,
      'monto' => $monto,
    ]);
  }
}
