<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Awobaz\Compoships\Compoships;

class Sucursal extends Model
{
  use HasRelationships;
  use Compoships;

  protected $table = 'sucursales';
  protected $primaryKey = ['cadena_id', 'sucursal_id'];
  public $incrementing = false;
  public $timestamps = false;

  protected $fillable = [
    'cadena_id',
    'sucursal_id',
    'nombre',
    'calle',
    'numero_ext',
    'numero_int',
    'ciudad',
    'colonia',
    'contacto',
    'latitud',
    'longitud',
    'location'
  ];

  protected $casts = [
    'latitud' => 'decimal:8',
    'longitud' => 'decimal:8',
  ];

  /**
   * Get full address
   */
  public function getDireccionAttribute(): string
  {
    $address = $this->calle . ' ' . $this->numero_ext;
    if ($this->numero_int) {
      $address .= ' Int. ' . $this->numero_int;
    }
    $address .= ', ' . $this->colonia;
    return $address;
  }

  public function cadena(): BelongsTo
  {
    return $this->belongsTo(CadenaFarmaceutica::class, 'cadena_id', 'cadena_id');
  }

  public function horarios(): HasMany
  {
    return $this->hasMany(SucursalHorario::class, ['cadena_id', 'sucursal_id'], ['cadena_id', 'sucursal_id']);
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
