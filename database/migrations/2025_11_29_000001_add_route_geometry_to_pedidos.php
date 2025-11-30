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
      // We use TEXT to store the encoded polyline string (or JSON if preferred)
      // Encoded polyline is efficient for storage.
      $table->text('route_geometry')->nullable()->after('estatus');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('pedidos', function (Blueprint $table) {
      $table->dropColumn('route_geometry');
    });
  }
};
