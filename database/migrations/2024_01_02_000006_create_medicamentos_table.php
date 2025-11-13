<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('medicamentos', function (Blueprint $table) {
      $table->id('id');
      $table->string('nombre', 100);
      $table->string('descripcion', 255)->nullable();
      $table->string('unidad_medida', 50);
      $table->string('unidades', 50);
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('medicamentos');
  }
};
