<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('notificaciones', function (Blueprint $table) {
      $table->id('notificacion_id');
      $table->unsignedBigInteger('user_id');
      $table->string('tipo', 50);
      $table->string('mensaje', 255);
      $table->dateTime('fecha_hora');
      $table->boolean('leida')->default(false);

      $table->foreign('user_id')
        ->references('user_id')
        ->on('users');
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('notificaciones');
  }
};
