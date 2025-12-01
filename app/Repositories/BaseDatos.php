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

use App\Services\Geo\GeoStrategyFactory;

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

  public function getSucursal(string $cadena_id, string $sucursal_id): ?DomainSucursal
  {
    $sucursal = Sucursal::where('cadena_id', $cadena_id)->where('sucursal_id', $sucursal_id)->first();
    $sucursal = DomainSucursal::crear($sucursal);
    return $sucursal;
  }

  /**
   * This method needs to be executed within a database transaction.
   * All operations performed inside this method are atomic and will be committed or rolled back as a single unit.
   */
  public function getInventario(string $cadena_id, string $sucursal_id, int $medId): ?LineaInventario
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

  public function getSucursalesPorCadena(string $cadena_id): Collection
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


  public function actualizarInventario(LineaInventario $ldi)
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

  public function guardarNotificacionSucursal(DomainSucursal $sucursal, int $folio_pedido, DomainNotificacion $notificacion)
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
      'cantidad' => $ldp->calcularCantidadSurtida(),
    ]);
  }

  public function guardarDetalleLineaPedido(DomainDetalleLineaPedido $dlp, int $folio_pedido): DetalleLineaPedido
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

  public function getPedidosPorSucursal(string $cadena_id, string $sucursal_id): Collection
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

  public function guardarMontoPenalizacion(int $folio, float $monto): void
  {
    PedidoPenalizacion::create([
      'folio_pedido' => $folio,
      'monto' => $monto,
    ]);
  }

  /**
   * Calculate the distance between two points in meters using Spatial functions.
   * 
   * @return float Distance in meters
   */
  public function calculateDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
  {
    $driver = DB::connection()->getDriverName();
    $strategy = GeoStrategyFactory::make($driver);

    $sql = $strategy->getDistanceSql();

    $result = DB::selectOne($sql, [$lng1, $lat1, $lng2, $lat2]);

    return $result->distance;
  }

  /**
   * Find the nearest branches that have stock for the given medications.
   * 
   * @param array $medicamentoIds List of medication IDs to check stock for.
   * @param float $originLat Origin branch latitude.
   * @param float $originLng Origin branch longitude.
   * @param string|null $excludeCadenaId Cadena ID of the branch to exclude.
   * @param string|null $excludeSucursalId Sucursal ID of the branch to exclude.
   * @param float $maxRadiusKm Maximum search radius in kilometers.
   * @return Collection<DomainSucursal>
   */
  public function buscarSucursalesCercanasConStock(
    array $medicamentoIds,
    float $originLat,
    float $originLng,
    ?string $excludeCadenaId = null,
    ?string $excludeSucursalId = null,
    float $maxRadiusKm
  ): Collection {
    $driver = DB::connection()->getDriverName();
    $strategy = GeoStrategyFactory::make($driver);

    $distanceSql = $strategy->getDistanceColumnSql();
    $maxDistanceMeters = $maxRadiusKm * 1000;

    $branches = DB::table('sucursales')
      ->join('inventarios', function ($join) {
        $join->on('sucursales.cadena_id', '=', 'inventarios.cadena_id')
          ->on('sucursales.sucursal_id', '=', 'inventarios.sucursal_id');
      })
      ->whereIn('inventarios.medicamento_id', $medicamentoIds)
      ->where('inventarios.stock_disponible', '>', 0)
      ->where(function ($query) use ($excludeCadenaId, $excludeSucursalId) {
        if ($excludeCadenaId && $excludeSucursalId) {
          $query->whereNot(function ($q) use ($excludeCadenaId, $excludeSucursalId) {
            $q->where('sucursales.cadena_id', '=', $excludeCadenaId)
              ->where('sucursales.sucursal_id', '=', $excludeSucursalId);
          });
        }
      })
      ->select(
        'sucursales.*',
        DB::raw("$distanceSql as distancia")
      )
      ->having('distancia', '<=', $maxDistanceMeters)
      ->orderBy('distancia', 'ASC')
      ->distinct()
      ->setBindings([$originLng, $originLat], 'select')
      ->get();

    return $branches->map(function ($branch) {
      return DomainSucursal::crear($branch);
    });
  }
}
