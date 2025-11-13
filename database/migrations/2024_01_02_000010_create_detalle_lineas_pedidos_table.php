<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('detalle_lineas_pedidos', function (Blueprint $table) {
      $table->unsignedBigInteger('pedido_id');
      $table->unsignedBigInteger('linea_id');
      $table->unsignedBigInteger('cadena_id');
      $table->unsignedBigInteger('sucursal_id');
      $table->integer('cantidad_asignada');
      $table->integer('cantidad_recolectada')->default(0);

      $table->primary(['pedido_id', 'linea_id', 'cadena_id', 'sucursal_id'], 'detalle_lineas_pedidos_primary');

      $table->foreign(['pedido_id', 'linea_id'])
        ->references(['pedido_id', 'linea_id'])
        ->on('lineas_pedidos');

      $table->foreign(['cadena_id', 'sucursal_id'])
        ->references(['cadena_id', 'sucursal_id'])
        ->on('sucursales');
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('detalle_lineas_pedidos');
  }
};
