<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('inventarios', function (Blueprint $table) {
      $table->unsignedBigInteger('cadena_id');
      $table->unsignedBigInteger('sucursal_id');
      $table->unsignedBigInteger('medicamento_id');
      $table->integer('cantidad');

      $table->primary(['cadena_id', 'sucursal_id', 'medicamento_id']);

      $table->foreign(['cadena_id', 'sucursal_id'])
        ->references(['cadena_id', 'sucursal_id'])
        ->on('sucursales');

      $table->foreign('medicamento_id')
        ->references('id')
        ->on('medicamentos');
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('inventarios');
  }
};
