<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LineaPedido extends Model
{
  protected $table = 'lineas_pedidos';
  protected $primaryKey = ['pedido_id', 'linea_id'];
  public $incrementing = false;
  public $timestamps = false;

  protected $fillable = [
    'pedido_id',
    'linea_id',
    'medicamento_id',
    'cantidad_solicitada',
    'precio_unitario',
  ];

  protected $casts = [
    'cantidad_solicitada' => 'integer',
    'precio_unitario' => 'decimal:2',
  ];

  public function pedido(): BelongsTo
  {
    return $this->belongsTo(Pedido::class, 'pedido_id', 'pedido_id');
  }

  public function medicamento(): BelongsTo
  {
    return $this->belongsTo(Medicamento::class, 'medicamento_id', 'id');
  }

  public function detalles(): HasMany
  {
    return $this->hasMany(DetalleLineaPedido::class, ['pedido_id', 'linea_id'], ['pedido_id', 'linea_id']);
  }
}
