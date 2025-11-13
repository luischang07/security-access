<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RutaRecoleccion extends Model
{
  protected $table = 'ruta_recoleccion';
  protected $primaryKey = ['pedido_id', 'cadena_id', 'sucursal_id'];
  public $incrementing = false;
  public $timestamps = false;

  protected $fillable = [
    'pedido_id',
    'cadena_id',
    'sucursal_id',
    'orden_visita',
    'estado_recoleccion',
    'fecha_hora_visita',
  ];

  protected $casts = [
    'orden_visita' => 'integer',
    'fecha_hora_visita' => 'datetime',
  ];

  public function pedido(): BelongsTo
  {
    return $this->belongsTo(Pedido::class, 'pedido_id', 'pedido_id');
  }

  public function sucursal(): BelongsTo
  {
    return $this->belongsTo(Sucursal::class, ['cadena_id', 'sucursal_id'], ['cadena_id', 'sucursal_id']);
  }
}
