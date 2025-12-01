<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Awobaz\Compoships\Compoships;

class DetalleLineaPedido extends Model
{
  use HasRelationships;
  use Compoships;
  protected $table = 'detalle_lineas_pedidos';
  protected $primaryKey = ['folio_pedido', 'id_linea_pedido', 'cadena_id', 'sucursal_id'];
  public $incrementing = false;
  public $timestamps = false;

  protected $fillable = [
    'folio_pedido',
    'cadena_id',
    'sucursal_id',
    'medicamento_id',
    'precio_unitario',
    'cantidad_surtida'
  ];

  protected $casts = [
    'precio_unitario' => 'decimal:2',
    'cantidad_surtida' => 'integer',
    'estatus' => 'integer',
  ];

  public function lineaPedido(): BelongsTo
  {
    return $this->belongsTo(LineaPedido::class, ['folio_pedido', 'medicamento_id'], ['folio_pedido', 'medicamento_id']);
  }

  public function sucursal(): BelongsTo
  {
    return $this->belongsTo(Sucursal::class, ['cadena_id', 'sucursal_id'], ['cadena_id', 'sucursal_id']);
  }
}
