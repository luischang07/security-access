<?php

namespace App\Repositories;

use App\Models\Inventario;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class InventarioRepository
{
  /**
   * Obtener inventario de una sucursal con paginación y búsqueda
   *
   * @param string $cadenaId
   * @param string $sucursalId
   * @param int $perPage
   * @param string|null $search
   * @return LengthAwarePaginator
   */
  public function getPaginatedInventoryForBranch(string $cadenaId, string $sucursalId, int $perPage = 20, ?string $search = null): LengthAwarePaginator
  {
    $query = Inventario::forBranch($cadenaId, $sucursalId)
      ->with('medicamento');

    if ($search) {
      $query->whereHas('medicamento', function ($q) use ($search) {
        $q->where('nombre', 'like', '%' . $search . '%');
      });
    }

    return $query->paginate($perPage);
  }

  /**
   * Eliminar un item del inventario
   *
   * @param string $cadenaId
   * @param string $sucursalId
   * @param int $medicamentoId
   * @return bool
   */
  public function deleteInventoryItem(string $cadenaId, string $sucursalId, int $medicamentoId): bool
  {
    return Inventario::where('cadena_id', $cadenaId)
      ->where('sucursal_id', $sucursalId)
      ->where('medicamento_id', $medicamentoId)
      ->delete();
  }

  /**
   * Buscar un item del inventario
   *
   * @param string $cadenaId
   * @param string $sucursalId
   * @param int $medicamentoId
   * @return Inventario|null
   */
  public function findInventoryItem(string $cadenaId, string $sucursalId, int $medicamentoId): ?Inventario
  {
    return Inventario::where('cadena_id', $cadenaId)
      ->where('sucursal_id', $sucursalId)
      ->where('medicamento_id', $medicamentoId)
      ->with('medicamento')
      ->first();
  }

  /**
   * Actualizar un item del inventario
   *
   * @param string $cadenaId
   * @param string $sucursalId
   * @param int $medicamentoId
   * @param array $data
   * @return bool
   */
  public function updateInventoryItem(string $cadenaId, string $sucursalId, int $medicamentoId, array $data): bool
  {
    return \Illuminate\Support\Facades\DB::transaction(function () use ($cadenaId, $sucursalId, $medicamentoId, $data) {
      // Lock the record for update
      $item = Inventario::where('cadena_id', $cadenaId)
        ->where('sucursal_id', $sucursalId)
        ->where('medicamento_id', $medicamentoId)
        ->lockForUpdate()
        ->first();

      if (!$item) {
        return false;
      }

      // Update using query builder to avoid composite key issues on model instance
      return Inventario::where('cadena_id', $cadenaId)
        ->where('sucursal_id', $sucursalId)
        ->where('medicamento_id', $medicamentoId)
        ->update($data);
    });
  }
}
