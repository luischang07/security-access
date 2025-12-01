<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class PedidoPenalizacion extends Model
{
  use HasRelationships;
  protected $table = 'pedido_penalizaciones';
  public $timestamps = true;

  protected $fillable = [
    'id',
    'folio_pedido',
    'monto',
  ];

}
