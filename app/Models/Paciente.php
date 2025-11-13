<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Paciente extends Model
{
  protected $table = 'pacientes';
  protected $primaryKey = 'user_id';
  public $incrementing = false;
  public $timestamps = false;

  protected $fillable = [
    'user_id',
    'monto_penalizacion',
  ];

  protected $casts = [
    'monto_penalizacion' => 'decimal:2',
  ];

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class, 'user_id', 'user_id');
  }

  public function pedidos(): HasMany
  {
    return $this->hasMany(Pedido::class, 'paciente_id', 'user_id');
  }

  public function notificaciones(): HasMany
  {
    return $this->hasMany(Notificacion::class, 'user_id', 'user_id');
  }
}
