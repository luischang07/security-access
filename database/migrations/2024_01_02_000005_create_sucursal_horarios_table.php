<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('sucursal_horarios', function (Blueprint $table) {
      $table->id();
      $table->string('cadena_id', 50);
      $table->string('sucursal_id', 50);
      $table->integer('dia_semana'); // 0=Sunday, 1=Monday, etc.
      $table->time('hora_apertura')->nullable();
      $table->time('hora_cierre')->nullable();
      $table->boolean('es_cerrado')->default(false);
      $table->timestamps();

      $table->foreign(['cadena_id', 'sucursal_id'])
        ->references(['cadena_id', 'sucursal_id'])
        ->on('sucursales')
        ->onDelete('cascade');
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('sucursal_horarios');
  }
};
