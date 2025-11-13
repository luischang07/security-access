<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('pedidos', function (Blueprint $table) {
      $table->id('pedido_id');
      $table->unsignedBigInteger('paciente_id');
      $table->unsignedBigInteger('cadena_id');
      $table->unsignedBigInteger('sucursal_id');
      $table->date('fecha_pedido');
      $table->date('fecha_entrega')->nullable();
      $table->string('estado', 50);
      $table->decimal('costo_total', 10, 2);

      $table->foreign('paciente_id')
        ->references('user_id')
        ->on('pacientes');

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
