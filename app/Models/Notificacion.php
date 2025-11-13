<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notificacion extends Model
{
  protected $table = 'notificaciones';
  protected $primaryKey = 'notificacion_id';
  public $timestamps = false;

  protected $fillable = [
    'user_id',
    'tipo',
    'mensaje',
    'fecha_hora',
    'leida',
  ];

  protected $casts = [
    'fecha_hora' => 'datetime',
    'leida' => 'boolean',
  ];

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class, 'user_id', 'user_id');
  }
}
