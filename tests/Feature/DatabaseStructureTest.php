<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class DatabaseStructureTest extends TestCase
{
  use RefreshDatabase;

  protected function setUp(): void
  {
    parent::setUp();
    $this->artisan('migrate');
  }

  /** @test */
  public function base_datos_tiene_tabla_usuarios()
  {
    $this->assertTrue(
      DB::getSchemaBuilder()->hasTable('users'),
      'La tabla users debe existir'
    );
  }

  /** @test */
  public function tabla_usuarios_tiene_columnas_requeridas()
  {
    $requiredColumns = [
      'id',
      'name',
      'email',
      'nip',
      'session_token',
      'session_expires_at',
      'last_login_at',
      'created_at',
      'updated_at'
    ];

    foreach ($requiredColumns as $column) {
      $this->assertTrue(
        DB::getSchemaBuilder()->hasColumn('users', $column),
        "La columna {$column} debe existir en la tabla users"
      );
    }
  }

  /** @test */
  public function base_datos_tiene_tabla_session_reset_tokens()
  {
    $this->assertTrue(
      DB::getSchemaBuilder()->hasTable('session_reset_tokens'),
      'La tabla session_reset_tokens debe existir'
    );
  }

  /** @test */
  public function tabla_tokens_tiene_columnas_requeridas()
  {
    $requiredColumns = ['email', 'token', 'created_at'];

    foreach ($requiredColumns as $column) {
      $this->assertTrue(
        DB::getSchemaBuilder()->hasColumn('session_reset_tokens', $column),
        "La columna {$column} debe existir en la tabla session_reset_tokens"
      );
    }
  }

  /** @test */
  public function puede_insertar_y_consultar_tokens()
  {
    // Arrange
    $email = 'test@example.com';
    $token = hash('sha256', 'test-token');

    // Act
    DB::table('session_reset_tokens')->insert([
      'email' => $email,
      'token' => $token,
      'created_at' => now(),
    ]);

    // Assert
    $this->assertDatabaseHas('session_reset_tokens', [
      'email' => $email,
      'token' => $token,
    ]);
  }

  /** @test */
  public function puede_eliminar_tokens_expirados()
  {
    // Arrange
    $validEmail = 'valid@example.com';
    $expiredEmail = 'expired@example.com';

    DB::table('session_reset_tokens')->insert([
      [
        'email' => $validEmail,
        'token' => hash('sha256', 'valid-token'),
        'created_at' => now()->subMinutes(30),
      ],
      [
        'email' => $expiredEmail,
        'token' => hash('sha256', 'expired-token'),
        'created_at' => now()->subHours(2),
      ],
    ]);

    // Act
    $deletedCount = DB::table('session_reset_tokens')
      ->where('created_at', '<', now()->subHours(1))
      ->delete();

    // Assert
    $this->assertEquals(1, $deletedCount);
    $this->assertDatabaseHas('session_reset_tokens', ['email' => $validEmail]);
    $this->assertDatabaseMissing('session_reset_tokens', ['email' => $expiredEmail]);
  }

  /** @test */
  public function puede_actualizar_token_existente()
  {
    // Arrange
    $email = 'test@example.com';
    $firstToken = hash('sha256', 'first-token');
    $secondToken = hash('sha256', 'second-token');

    // Act - Primera inserción
    DB::table('session_reset_tokens')->updateOrInsert(
      ['email' => $email],
      ['token' => $firstToken, 'created_at' => now()]
    );

    // Act - Actualización
    DB::table('session_reset_tokens')->updateOrInsert(
      ['email' => $email],
      ['token' => $secondToken, 'created_at' => now()]
    );

    // Assert
    $this->assertDatabaseHas('session_reset_tokens', [
      'email' => $email,
      'token' => $secondToken,
    ]);

    $this->assertDatabaseMissing('session_reset_tokens', [
      'email' => $email,
      'token' => $firstToken,
    ]);

    // Solo debe existir un token para este email
    $count = DB::table('session_reset_tokens')->where('email', $email)->count();
    $this->assertEquals(1, $count);
  }

  /** @test */
  public function puede_obtener_estadisticas_basicas()
  {
    // Arrange
    DB::table('session_reset_tokens')->insert([
      ['email' => 'user1@example.com', 'token' => hash('sha256', 'token1'), 'created_at' => now()],
      ['email' => 'user2@example.com', 'token' => hash('sha256', 'token2'), 'created_at' => now()],
      ['email' => 'user3@example.com', 'token' => hash('sha256', 'token3'), 'created_at' => now()->subHours(2)],
    ]);

    // Act
    $totalTokens = DB::table('session_reset_tokens')->count();
    $recentTokens = DB::table('session_reset_tokens')
      ->where('created_at', '>', now()->subHours(1))
      ->count();

    // Assert
    $this->assertEquals(3, $totalTokens);
    $this->assertEquals(2, $recentTokens);
  }
}
