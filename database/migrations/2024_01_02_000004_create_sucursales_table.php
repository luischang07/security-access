<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('sucursales', function (Blueprint $table) {
      $table->string('cadena_id', 50);
      $table->string('sucursal_id', 50);
      $table->string('nombre', 100);
      $table->string('calle', 100);
      $table->string('numero_ext', 10);
      $table->string('numero_int', 10)->nullable();
      $table->string('colonia', 100);
      $table->decimal('latitud', 10, 8);
      $table->decimal('longitud', 11, 8);

      $table->primary(['cadena_id', 'sucursal_id']);
      $table->foreign('cadena_id')->references('cadena_id')->on('cadena_farmaceuticas')->onDelete('cascade');
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('sucursales');
  }
};
