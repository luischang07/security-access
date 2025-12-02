<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::table('pedidos', function (Blueprint $table) {
      $table->dateTime('fecha_surtido')->nullable()->after('fecha_recoleccion');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('pedidos', function (Blueprint $table) {
      $table->dropColumn('fecha_surtido');
    });
  }
};
