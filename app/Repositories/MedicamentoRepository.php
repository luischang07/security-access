<?php

namespace App\Repositories;

use App\Models\Medicamento;

class MedicamentoRepository
{
  /**
   * Search medications by name
   *
   * @param string $query
   * @param int $limit
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public function searchByName(string $query, int $limit = 10)
  {
    return Medicamento::where('nombre', 'like', "%{$query}%")
      ->select('id', 'nombre', 'unidad_medida')
      ->limit($limit)
      ->get();
  }
}
