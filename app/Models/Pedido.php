<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Pedido extends Model
{
  use HasRelationships;

  public const ESTATUS_CONFIRMADO = 'confirmado';
  public const ESTATUS_COMPLETADO = 'completado';
  public const ESTATUS_SURTIDO = 'surtido';
  public const ESTATUS_CANCELADO = 'cancelado';

  protected $table = 'pedidos';
  protected $primaryKey = 'folio_pedido';
  public $incrementing = true;
  protected $keyType = 'int';
  public $timestamps = false;

  protected $fillable = [
    'folio_pedido',
    'paciente_id',
    'cadena_id',
    'sucursal_id',
    'cedula_profesional',
    'fecha_pedido',
    'fecha_recoleccion',
    'estatus',
    'costo_total',
    'route_geometry',
  ];

  protected $casts = [
    'fecha_pedido' => 'datetime',
    'fecha_recoleccion' => 'datetime',
    'costo_total' => 'decimal:2',
  ];

  public function paciente(): BelongsTo
  {
    return $this->belongsTo(Paciente::class, 'paciente_id', 'user_id');
  }

  public function sucursal()
  {
    return Sucursal::where('cadena_id', $this->cadena_id)
      ->where('sucursal_id', $this->sucursal_id)
      ->first();
  }

  public function getSucursalAttribute()
  {
    if (!isset($this->attributes['_sucursal_loaded'])) {
      $this->attributes['_sucursal'] = Sucursal::where('cadena_id', $this->cadena_id)
        ->where('sucursal_id', $this->sucursal_id)
        ->first();
      $this->attributes['_sucursal_loaded'] = true;
    }
    return $this->attributes['_sucursal'] ?? null;
  }

  public function lineasPedidos(): HasMany
  {
    return $this->hasMany(LineaPedido::class, 'folio_pedido', 'folio_pedido')->with(['medicamento', 'detalles']);
  }

  public function rutaRecoleccion(): HasMany
  {
    return $this->hasMany(RutaRecoleccion::class, 'folio_pedido', 'folio_pedido');
  }

  public function belongsToPatient(int $userId): bool
  {
    return $this->paciente_id === $userId;
  }

  public function penalizacion()
  {
    return $this->hasOne(PedidoPenalizacion::class, 'folio_pedido', 'folio_pedido');
  }

  public function scopeForPatient($query, int $userId)
  {
    return $query->where('paciente_id', $userId);
  }
}
