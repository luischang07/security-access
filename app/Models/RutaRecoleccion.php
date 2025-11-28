<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class RutaRecoleccion extends Model
{
  use HasRelationships;
  protected $table = 'ruta_recoleccion';
  protected $primaryKey = ['folio_pedido', 'cadena_id', 'sucursal_id'];
  public $incrementing = false;
  public $timestamps = false;

  protected $fillable = [
    'cadena_id',
    'sucursal_id',
    'folio_pedido',
    'orden_recoleccion',
  ];

  protected $casts = [
    'orden_recoleccion' => 'integer',
  ];

  public function pedido(): BelongsTo
  {
    return $this->belongsTo(Pedido::class, 'folio_pedido', 'folio_pedido');
  }

  public function sucursal(): BelongsTo
  {
    return $this->belongsTo(Sucursal::class, ['cadena_id', 'sucursal_id'], ['cadena_id', 'sucursal_id']);
  }
}
