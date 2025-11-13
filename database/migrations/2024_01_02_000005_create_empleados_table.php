<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('empleados', function (Blueprint $table) {
      $table->foreignId('user_id')->primary()->constrained('users', 'user_id');
      $table->string('cadena_id', 50);
      $table->string('sucursal_id', 50);
      $table->date('fecha_ingreso');

      $table->foreign(['cadena_id', 'sucursal_id'])
        ->references(['cadena_id', 'sucursal_id'])
        ->on('sucursales');
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('empleados');
  }
};
