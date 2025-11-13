<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('pacientes', function (Blueprint $table) {
      $table->foreignId('user_id')->primary()->constrained('users', 'user_id');
      $table->decimal('monto_penalizacion', 8, 2)->default(0.00);
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('pacientes');
  }
};
