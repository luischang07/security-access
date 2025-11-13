<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleLineaPedido extends Model
{
  protected $table = 'detalle_lineas_pedidos';
  protected $primaryKey = ['pedido_id', 'linea_id', 'cadena_id', 'sucursal_id'];
  public $incrementing = false;
  public $timestamps = false;

  protected $fillable = [
    'pedido_id',
    'linea_id',
    'cadena_id',
    'sucursal_id',
    'cantidad_asignada',
    'cantidad_recolectada',
  ];

  protected $casts = [
    'cantidad_asignada' => 'integer',
    'cantidad_recolectada' => 'integer',
  ];

  public function lineaPedido(): BelongsTo
  {
    return $this->belongsTo(LineaPedido::class, ['pedido_id', 'linea_id'], ['pedido_id', 'linea_id']);
  }

  public function sucursal(): BelongsTo
  {
    return $this->belongsTo(Sucursal::class, ['cadena_id', 'sucursal_id'], ['cadena_id', 'sucursal_id']);
  }
}
