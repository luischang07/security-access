<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventario extends Model
{
  protected $table = 'inventarios';
  protected $primaryKey = ['cadena_id', 'sucursal_id', 'medicamento_id'];
  public $incrementing = false;
  public $timestamps = false;

  protected $fillable = [
    'cadena_id',
    'sucursal_id',
    'medicamento_id',
    'cantidad',
  ];

  protected $casts = [
    'cantidad' => 'integer',
  ];

  public function sucursal(): BelongsTo
  {
    return $this->belongsTo(Sucursal::class, ['cadena_id', 'sucursal_id'], ['cadena_id', 'sucursal_id']);
  }

  public function medicamento(): BelongsTo
  {
    return $this->belongsTo(Medicamento::class, 'medicamento_id', 'id');
  }
}
