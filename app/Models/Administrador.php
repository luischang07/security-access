<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Administrador extends Model
{
  protected $table = 'administradores';
  protected $primaryKey = 'user_id';
  public $incrementing = false;
  public $timestamps = false;

  protected $fillable = [
    'user_id',
    'rol_admin',
    'ultimo_login',
  ];

  protected $casts = [
    'ultimo_login' => 'datetime',
  ];

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class, 'user_id', 'user_id');
  }
}
