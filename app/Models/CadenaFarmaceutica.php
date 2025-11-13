<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CadenaFarmaceutica extends Model
{
  protected $table = 'cadena_farmaceuticas';
  protected $primaryKey = 'cadena_id';
  public $timestamps = false;

  protected $fillable = [
    'razon_social',
    'name',
  ];

  public function sucursales(): HasMany
  {
    return $this->hasMany(Sucursal::class, 'cadena_id', 'cadena_id');
  }
}
