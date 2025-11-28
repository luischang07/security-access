<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class LineaPedido extends Model
{
  use HasRelationships;
  protected $table = 'lineas_pedidos';
  protected $primaryKey = ['folio_pedido', 'medicamento_id'];
  public $incrementing = false;
  public $timestamps = false;

  protected $fillable = [
    'folio_pedido',
    'medicamento_id',
    'cantidad',
  ];

  protected $casts = [
    'cantidad' => 'integer',
  ];

  public function pedido(): BelongsTo
  {
    return $this->belongsTo(Pedido::class, 'folio_pedido', 'folio_pedido');
  }

  public function medicamento(): BelongsTo
  {
    return $this->belongsTo(Medicamento::class, 'medicamento_id', 'id');
  }

  public function detalles(): HasMany
  {
    return $this->hasMany(DetalleLineaPedido::class, ['folio_pedido', 'medicamento_id'], ['folio_pedido', 'medicamento_id']);
  }
}
