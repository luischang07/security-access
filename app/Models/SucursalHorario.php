<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Awobaz\Compoships\Compoships;

class SucursalHorario extends Model
{
  use Compoships;

  protected $table = 'sucursal_horarios';

  protected $fillable = [
    'cadena_id',
    'sucursal_id',
    'dia_semana',
    'hora_apertura',
    'hora_cierre',
    'es_cerrado',
  ];

  protected $casts = [
    'es_cerrado' => 'boolean',
    'dia_semana' => 'integer',
  ];

  public function sucursal(): BelongsTo
  {
    return $this->belongsTo(Sucursal::class, ['cadena_id', 'sucursal_id'], ['cadena_id', 'sucursal_id']);
  }
}
