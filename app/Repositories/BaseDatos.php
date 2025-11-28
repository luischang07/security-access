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


use App\Models\Pedido;
use App\Domain\Pedido as DomainPedido;

use App\Models\LineaPedido;
use App\Domain\LineaPedido as DomainLineaPedido;

use App\Domain\DetalleLineaPedido as DomainDetalleLineaPedido;

use Illuminate\Support\Collection;
class BaseDatos
{

  public function obtenerTodasSucursales()
  {
    $sucursales = Sucursal::all();
    return $sucursales;
  }
  public function obtenerSucursal($cadena_id, $sucursal_id)
  {
    $sucursal = Sucursal::where('cadena_id', $cadena_id)->where('sucursal_id', $sucursal_id)->first();
    $sucursal = DomainSucursal::crear($sucursal);
    return $sucursal;
  }

  public function obtenerInventario($cadena_id, $sucursal_id, $medId)
  {
    $data = Inventario::where('cadena_id', $cadena_id)->where('sucursal_id', $sucursal_id)->where('medicamento_id', $medId)->first();

    return new LineaInventario($data->cadena_id, $data->sucursal_id, $data->medicamento_id, $data->stock_disponible, $data->precio_unitario);
  }
  public function obtenerCadenas()
  {
    return CadenaFarmaceutica::select('cadena_id', 'nombre')->orderBy('nombre')->get();
  }
  // obtener sucursales por cadena
  public function obtenerSucursalesPorCadena($cadena_id)
  {
    return Sucursal::where('cadena_id', $cadena_id)->get();
  }

  public function obtenerMedicamento($medId)
  {
    $medicamento = Medicamento::where('id', $medId)->first();
    return new med($medicamento->id, $medicamento->nombre, $medicamento->descripcion, $medicamento->unidad_medida, $medicamento->unidades);
  }

  public function obtenerSucursalesOrdenadas($cadena_id, $sucursal_id)
  {
    // 1) Obtener la sucursal base
    $base = Sucursal::where('cadena_id', $cadena_id)
      ->where('sucursal_id', $sucursal_id)
      ->firstOrFail();

    $lat = $base->latitud;
    $lng = $base->longitud;

    // 2) Consultar TODAS las sucursales ordenadas por distancia
    $sucursales = Sucursal::select('*')
      ->selectRaw("
                (6371 * acos(
                    cos(radians(?)) *
                    cos(radians(latitud)) *
                    cos(radians(longitud) - radians(?)) +
                    sin(radians(?)) *
                    sin(radians(latitud))
                )) AS distancia
            ", [$lat, $lng, $lat])
      ->whereNot(function ($q) use ($cadena_id, $sucursal_id) {
        $q->where('cadena_id', $cadena_id)
          ->where('sucursal_id', $sucursal_id);
      })
      ->orderBy('distancia', 'ASC')
      ->get();

    $collectionSucursales = collect();

    foreach ($sucursales as $sucursal) {
      $collectionSucursales->push(DomainSucursal::crear($sucursal));
    }

    return $collectionSucursales;
  }

  public function buscarMedicamentosPorNombre(string $nombre)
  {
    return Medicamento::where('nombre', 'like', '%' . $nombre . '%')
      ->orderBy('nombre')
      ->limit(10)
      ->get(['id', 'nombre', 'unidad_medida', 'unidades']);
  }

  public function actualizarInventario($cantidad, $med_id, $sucursal)
  {
    $query = Inventario::where('cadena_id', $sucursal->getCadenaId())
      ->where('sucursal_id', $sucursal->getSucursalId())
      ->where('medicamento_id', $med_id);

    $stockActual = $query->value('stock_disponible');

    if (!is_null($stockActual) && $stockActual >= $cantidad) {

      $query->decrement('stock_disponible', $cantidad);

      info("Inventario descontado. Sucursal: {$sucursal->getSucursalId()}, Med: $med_id, Cant: $cantidad");

      return false;
    }

    return true;
  }


  public function guardarPedido(DomainPedido $pedido): Pedido
  {
    return Pedido::create([
      'paciente_id' => $pedido->getPacienteId(),
      'cadena_id' => $pedido->getSucursal()->getCadenaId(),
      'sucursal_id' => $pedido->getSucursal()->getSucursalId(),
      'cedula_profesional' => $pedido->getCedulaProfesional(),
      'fecha_pedido' => $pedido->getFechaPedido(),
      'fecha_recoleccion' => $pedido->getFechaRecoleccion(),
      'estatus' => $pedido->getEstatus(),
      'costo_total' => $pedido->getCostoTotal(),
    ]);
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
      'id_linea_pedido' => $id_lineapedido,
      'cadena_id' => $dlp->getSucursal()->getCadenaId(),
      'sucursal_id' => $dlp->getSucursal()->getSucursalId(),
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

  public function getPedidos($user_id)
  {
    $pedidos = Pedido::where('paciente_id', $user_id)->with("lineasPedidos")->get();

    $pedidos = $pedidos->map(function ($pedido) {
      return DomainPedido::crear($pedido);
    });

    return $pedidos;
  }
}
