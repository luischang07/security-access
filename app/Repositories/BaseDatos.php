<?php

namespace App\Repositories;
use App\Models\Sucursal;
use App\Models\Inventario;
use App\Models\CadenaFarmaceutica;
use App\Domain\LineaInventario;
use App\Models\Medicamento;
use App\Domain\Sucursal as DomainSucursal;
use App\Domain\Medicamento as med;


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
    $inventario = Inventario::where('cadena_id', $sucursal->getCadenaId())
      ->where('sucursal_id', $sucursal->getSucursalId())
      ->where('medicamento_id', $med_id)
      ->first();

    if ($inventario && $inventario->stock_disponible >= $cantidad) {
      $inventario->stock_disponible -= $cantidad;
      $inventario->save();
      return true;
    }
    return false;
  }
}
