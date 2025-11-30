<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('lineas_pedidos', function (Blueprint $table) {

      $table->unsignedBigInteger('folio_pedido');
      $table->unsignedBigInteger('medicamento_id');
      $table->integer('cantidad');

      // Llave primaria compuesta
      $table->primary(['folio_pedido', 'medicamento_id']);

      // Foreign keys
      $table->foreign('folio_pedido')
        ->references('folio_pedido')
        ->on('pedidos');

      $table->foreign('medicamento_id')
        ->references('id')
        ->on('medicamentos');
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('lineas_pedidos');
  }
};
