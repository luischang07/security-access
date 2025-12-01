<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('detalle_lineas_pedidos', function (Blueprint $table) {
      $table->unsignedBigInteger('folio_pedido');
      $table->string('cadena_id', 50);
      $table->string('sucursal_id', 50);
      $table->unsignedBigInteger('medicamento_id');
      $table->decimal('precio_unitario', 10, 2);
      $table->integer('cantidad_surtida');

      $table->primary(['folio_pedido', 'cadena_id', 'sucursal_id', 'medicamento_id'], 'detalle_lineas_pedidos_primary');

      $table->foreign(['folio_pedido', 'medicamento_id'])
        ->references(['folio_pedido', 'medicamento_id'])
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
