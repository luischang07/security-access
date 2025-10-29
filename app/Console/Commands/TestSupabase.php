<?php

namespace App\Console\Commands;

use App\Services\SupabaseService;
use Illuminate\Console\Command;

class TestSupabase extends Command
{
  protected $signature = 'supabase:test';
  protected $description = 'Probar funcionalidades de Supabase';

  public function __construct(
    private readonly SupabaseService $supabase
  ) {
    parent::__construct();
  }

  public function handle()
  {
    $this->info("🧪 === PRUEBAS DE SUPABASE ===");

    // Test 1: Insertar datos
    $this->testInsert();

    // Test 2: Leer datos
    $this->testSelect();

    // Test 3: Actualizar datos
    $this->testUpdate();

    // Test 4: Eliminar datos
    $this->testDelete();

    $this->info("\n✅ ¡Todas las pruebas completadas!");

    return Command::SUCCESS;
  }

  private function testInsert()
  {
    $this->info("\n1️⃣ Probando INSERT...");

    $testData = [
      'name' => 'Usuario Test Supabase',
      'email' => 'test-supabase@example.com',
      'nip' => bcrypt('test123'),
      'login_attempts' => 0
    ];

    $result = $this->supabase->insert('users', $testData);

    if ($result) {
      $this->info("✅ INSERT exitoso");
      $this->line("   ID creado: " . ($result[0]['id'] ?? 'N/A'));
    } else {
      $this->error("❌ Error en INSERT");
    }
  }

  private function testSelect()
  {
    $this->info("\n2️⃣ Probando SELECT...");

    $result = $this->supabase->select('users', [
      'email' => 'test-supabase@example.com'
    ], ['id', 'name', 'email']);

    if ($result && count($result) > 0) {
      $this->info("✅ SELECT exitoso");
      $this->table(
        ['ID', 'Nombre', 'Email'],
        [[
          $result[0]['id'] ?? 'N/A',
          $result[0]['name'] ?? 'N/A',
          $result[0]['email'] ?? 'N/A'
        ]]
      );
    } else {
      $this->error("❌ Error en SELECT o no se encontraron datos");
    }
  }

  private function testUpdate()
  {
    $this->info("\n3️⃣ Probando UPDATE...");

    $result = $this->supabase->update(
      'users',
      ['email' => 'test-supabase@example.com'],
      ['name' => 'Usuario Test Supabase ACTUALIZADO']
    );

    if ($result) {
      $this->info("✅ UPDATE exitoso");
      $this->line("   Registros actualizados: " . count($result));
    } else {
      $this->error("❌ Error en UPDATE");
    }
  }

  private function testDelete()
  {
    $this->info("\n4️⃣ Probando DELETE...");

    $result = $this->supabase->delete('users', [
      'email' => 'test-supabase@example.com'
    ]);

    if ($result) {
      $this->info("✅ DELETE exitoso");
    } else {
      $this->error("❌ Error en DELETE");
    }
  }
}
