<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Inventario extends Model
{
  use HasRelationships;
  protected $table = 'inventarios';
  protected $primaryKey = ['cadena_id', 'sucursal_id', 'medicamento_id'];
  public $incrementing = false;
  public $timestamps = false;

  protected $fillable = [
    'cadena_id',
    'sucursal_id',
    'medicamento_id',
    'stock_disponible',
  ];

  protected $casts = [
    'stock_disponible' => 'integer',
  ];

  public function sucursal(): BelongsTo
  {
    return $this->belongsTo(Sucursal::class, ['cadena_id', 'sucursal_id'], ['cadena_id', 'sucursal_id']);
  }

  public function medicamento(): BelongsTo
  {
    return $this->belongsTo(Medicamento::class, 'medicamento_id', 'id');
  }

  /**
   * Scope para filtrar inventario por sucursal
   */
  public function scopeForBranch($query, string $cadenaId, string $sucursalId)
  {
    return $query->where('cadena_id', $cadenaId)
      ->where('sucursal_id', $sucursalId);
  }
}
