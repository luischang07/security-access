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
    Schema::create('users', function (Blueprint $table) {
      $table->id('user_id');
      $table->string('nombre', 100);
      $table->string('apellido', 100);
      $table->string('correo', 100)->unique();
      $table->string('password', 255);
      $table->timestamp('email_verified_at')->nullable();
      $table->string('session_token', 255)->nullable();
      $table->timestamp('session_expires_at')->nullable();
      $table->timestamp('ultimo_login')->nullable();
      $table->timestamp('ultimo_cierre_sesion')->nullable();
      $table->tinyInteger('login_attempts')->default(0);
      $table->timestamp('login_attempts_reset_at')->nullable();
      $table->timestamp('locked_until')->nullable();
      $table->rememberToken();
      $table->timestamps();
    });

    Schema::create('password_reset_tokens', function (Blueprint $table) {
      $table->string('correo')->primary();
      $table->string('token');
      $table->timestamp('created_at')->nullable();
    });

    Schema::create('sessions', function (Blueprint $table) {
      $table->string('id')->primary();
      $table->foreignId('user_id')->nullable()->index();
      $table->string('ip_address', 45)->nullable();
      $table->text('user_agent')->nullable();
      $table->longText('payload');
      $table->integer('last_activity')->index();
    });

    Schema::create('session_reset_tokens', function (Blueprint $table) {
      $table->string('correo')->primary();
      $table->string('token');
      $table->timestamp('created_at')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('users');
    Schema::dropIfExists('password_reset_tokens');
    Schema::dropIfExists('sessions');
    Schema::dropIfExists('session_reset_tokens');
  }
};
