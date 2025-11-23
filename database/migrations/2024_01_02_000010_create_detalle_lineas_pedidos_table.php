<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('detalle_lineas_pedidos', function (Blueprint $table) {
      $table->String('folio_pedido');
      $table->unsignedBigInteger('id_linea_pedido');
      $table->string('cadena_id', 50);
      $table->string('sucursal_id', 50);
      $table->decimal('precio_unitario', 10, 2);
      $table->integer('cantidad_surtida');
      $table->integer('estatus')->default(0);

      $table->primary(['folio_pedido', 'id_linea_pedido'], 'detalle_lineas_pedidos_primary');

      $table->foreign(['folio_pedido', 'id_linea_pedido'])
        ->references(['folio_pedido', 'id_linea_pedido'])
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
