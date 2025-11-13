<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sucursal extends Model
{
  protected $table = 'sucursales';
  protected $primaryKey = ['cadena_id', 'sucursal_id'];
  public $incrementing = false;
  public $timestamps = false;

  protected $fillable = [
    'cadena_id',
    'sucursal_id',
    'nombre',
    'direccion',
    'latitud',
    'longitud',
  ];

  protected $casts = [
    'latitud' => 'decimal:8',
    'longitud' => 'decimal:8',
  ];

  public function cadena(): BelongsTo
  {
    return $this->belongsTo(CadenaFarmaceutica::class, 'cadena_id', 'cadena_id');
  }

  public function empleados(): HasMany
  {
    return $this->hasMany(Empleado::class, ['cadena_id', 'sucursal_id'], ['cadena_id', 'sucursal_id']);
  }

  public function inventarios(): HasMany
  {
    return $this->hasMany(Inventario::class, ['cadena_id', 'sucursal_id'], ['cadena_id', 'sucursal_id']);
  }

  public function pedidos(): HasMany
  {
    return $this->hasMany(Pedido::class, ['cadena_id', 'sucursal_id'], ['cadena_id', 'sucursal_id']);
  }
}
