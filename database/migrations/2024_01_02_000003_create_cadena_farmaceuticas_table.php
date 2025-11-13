<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('cadena_farmaceuticas', function (Blueprint $table) {
      $table->string('cadena_id', 50)->primary();
      $table->string('razon_social', 100);
      $table->string('name', 100);
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('cadena_farmaceuticas');
  }
};
