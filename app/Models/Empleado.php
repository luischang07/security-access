<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Empleado extends Model
{
  protected $table = 'empleados';
  protected $primaryKey = 'user_id';
  public $incrementing = false;
  public $timestamps = false;

  protected $fillable = [
    'user_id',
    'cadena_id',
    'sucursal_id',
    'fecha_ingreso',
  ];

  protected $casts = [
    'fecha_ingreso' => 'date',
  ];

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class, 'user_id', 'user_id');
  }

  public function sucursal(): BelongsTo
  {
    return $this->belongsTo(Sucursal::class, ['cadena_id', 'sucursal_id'], ['cadena_id', 'sucursal_id']);
  }
}
