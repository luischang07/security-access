<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Medicamento extends Model
{
  protected $table = 'medicamentos';
  protected $primaryKey = 'id';
  public $timestamps = false;

  protected $fillable = [
    'nombre',
    'descripcion',
    'unidad_medida',
    'unidades',
  ];

  public function inventarios(): HasMany
  {
    return $this->hasMany(Inventario::class, 'medicamento_id', 'id');
  }

  public function lineasPedidos(): HasMany
  {
    return $this->hasMany(LineaPedido::class, 'medicamento_id', 'id');
  }
}
