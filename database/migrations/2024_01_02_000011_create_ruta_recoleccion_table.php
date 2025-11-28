<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('ruta_recoleccion', function (Blueprint $table) {

      $table->string('cadena_id', 50);
      $table->string('sucursal_id', 50);
      $table->unsignedBigInteger('folio_pedido');
      $table->integer('orden_recoleccion');

      $table->primary(['cadena_id', 'sucursal_id', 'folio_pedido'], 'ruta_recoleccion_primary');

      $table->foreign('folio_pedido')
        ->references('folio_pedido')
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
