<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
  /** @use HasFactory<\Database\Factories\UserFactory> */
  use HasFactory, Notifiable;

  protected $primaryKey = 'user_id';

  /**
   * The attributes that are mass assignable.
   *
   * @var list<string>
   */
  protected $fillable = [
    'nombre',
    'correo',
    'direccion',
    'password',
    'session_token',
    'session_expires_at',
    'ultimo_login',
    'ultimo_cierre_sesion',
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var list<string>
   */
  protected $hidden = [
    'password',
    'session_token',
    'remember_token',
  ];

  /**
   * Get the attributes that should be cast.
   *
   * @return array<string, string>
   */
  protected function casts(): array
  {
    return [
      'email_verified_at' => 'datetime',
      'password' => 'hashed',
      'ultimo_login' => 'datetime',
      'ultimo_cierre_sesion' => 'datetime',
      'session_expires_at' => 'datetime',
    ];
  }

  public function administrador(): HasOne
  {
    return $this->hasOne(Administrador::class, 'user_id', 'user_id');
  }

  public function paciente(): HasOne
  {
    return $this->hasOne(Paciente::class, 'user_id', 'user_id');
  }

  public function empleado(): HasOne
  {
    return $this->hasOne(Empleado::class, 'user_id', 'user_id');
  }

  public function notificaciones()
  {
    return $this->hasMany(Notificacion::class, 'user_id', 'user_id');
  }
}
