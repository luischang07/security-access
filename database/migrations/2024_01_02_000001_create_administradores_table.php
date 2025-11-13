<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('administradores', function (Blueprint $table) {
      $table->foreignId('user_id')->primary()->constrained('users', 'user_id');
      $table->string('rol_admin', 50)->nullable();
      $table->timestamp('ultimo_login')->nullable();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('administradores');
  }
};
