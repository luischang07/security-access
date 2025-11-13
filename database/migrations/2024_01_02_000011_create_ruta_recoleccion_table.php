<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('ruta_recoleccion', function (Blueprint $table) {
      $table->unsignedBigInteger('pedido_id');
      $table->unsignedBigInteger('cadena_id');
      $table->unsignedBigInteger('sucursal_id');
      $table->integer('orden_visita');
      $table->string('estado_recoleccion', 50);
      $table->dateTime('fecha_hora_visita')->nullable();

      $table->primary(['pedido_id', 'cadena_id', 'sucursal_id'], 'ruta_recoleccion_primary');

      $table->foreign('pedido_id')
        ->references('pedido_id')
        ->on('pedidos');

      $table->foreign(['cadena_id', 'sucursal_id'])
        ->references(['cadena_id', 'sucursal_id'])
        ->on('sucursales');
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('ruta_recoleccion');
  }
};
