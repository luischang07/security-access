<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pedido extends Model
{
  protected $table = 'pedidos';
  protected $primaryKey = 'pedido_id';
  public $timestamps = false;

  protected $fillable = [
    'paciente_id',
    'cadena_id',
    'sucursal_id',
    'fecha_pedido',
    'fecha_entrega',
    'estado',
    'costo_total',
  ];

  protected $casts = [
    'fecha_pedido' => 'date',
    'fecha_entrega' => 'date',
    'costo_total' => 'decimal:2',
  ];

  public function paciente(): BelongsTo
  {
    return $this->belongsTo(Paciente::class, 'paciente_id', 'user_id');
  }

  public function sucursal(): BelongsTo
  {
    return $this->belongsTo(Sucursal::class, ['cadena_id', 'sucursal_id'], ['cadena_id', 'sucursal_id']);
  }

  public function lineasPedidos(): HasMany
  {
    return $this->hasMany(LineaPedido::class, 'pedido_id', 'pedido_id');
  }

  public function rutaRecoleccion(): HasMany
  {
    return $this->hasMany(RutaRecoleccion::class, 'pedido_id', 'pedido_id');
  }
}
