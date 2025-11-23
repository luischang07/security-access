<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('pedidos', function (Blueprint $table) {
      $table->String('folio_pedido')->primary();
      $table->foreign('paciente_id')
        ->references('user_id')
        ->on('pacientes');

      $table->string('cadena_id', 50);
      $table->string('sucursal_id', 50);
      $table->unsignedBigInteger('paciente_id');
      $table->string('cedula_profesional', 50);
      $table->date('fecha_pedido');
      $table->date('fecha_entrega')->nullable();
      $table->string('estatus', 50);
      $table->decimal('costo_total', 10, 2);


      $table->foreign(['cadena_id', 'sucursal_id'])
        ->references(['cadena_id', 'sucursal_id'])
        ->on('sucursales');
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('pedidos');
  }
};
