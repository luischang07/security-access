<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Sucursal;
use App\Models\Medicamento;
use App\Models\Inventario;
use App\Models\CadenaFarmaceutica;
use App\Services\GeoLocationService;
use App\Services\Modelos\GestorDeSurtido;
use App\Services\Modelos\SucursalService;
use App\Services\Modelos\PedidoService;
use App\Domain\Pedido;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class DistributedFulfillmentTest extends TestCase
{
  // Do NOT use RefreshDatabase here because this test may run against an existing (non-test) database.
  // Instead, we manually wrap the test in a transaction (see DB::beginTransaction/rollBack) to ensure changes are rolled back.

  public function test_finds_nearest_branch_with_stock()
  {
    DB::beginTransaction();
    try {
      // 1. Setup Data
      // Create Chain
      $chainId = 'CAD' . rand(1000, 9999);
      $cadena = CadenaFarmaceutica::create([
        'cadena_id' => $chainId,
        'nombre' => 'Test Chain ' . $chainId,
        'razon_social' => 'Test Chain SA'
      ]);

      // Create 3 Branches:
      // A: Primary (No Stock) - Located at 0,0
      // B: Near (Has Stock) - Located at 0.01, 0.01 (approx 1.5km)
      // C: Far (Has Stock) - Located at 0.1, 0.1 (approx 15km)

      $sucAId = rand(10000, 19999);
      $sucBId = rand(20000, 29999);
      $sucCId = rand(30000, 39999);

      $commonFields = [
        'calle' => 'Test Street',
        'numero_ext' => '123',
        'ciudad' => 'Test City',
        'colonia' => 'Test Colony',
        'contacto' => '1234567890',
        'numero_int' => null
      ];

      $sucA = Sucursal::create(array_merge($commonFields, [
        'cadena_id' => $cadena->cadena_id,
        'sucursal_id' => $sucAId,
        'nombre' => 'Branch A',
        'latitud' => 0,
        'longitud' => 0,
        'location' => DB::raw("Point(0, 0)")
      ]));

      $sucB = Sucursal::create(array_merge($commonFields, [
        'cadena_id' => $cadena->cadena_id,
        'sucursal_id' => $sucBId,
        'nombre' => 'Branch B',
        'latitud' => 0.01,
        'longitud' => 0.01,
        'location' => DB::raw("Point(0.01, 0.01)")
      ]));

      $sucC = Sucursal::create(array_merge($commonFields, [
        'cadena_id' => $cadena->cadena_id,
        'sucursal_id' => $sucCId,
        'nombre' => 'Branch C',
        'latitud' => 0.1,
        'longitud' => 0.1,
        'location' => DB::raw("Point(0.1, 0.1)")
      ]));

      // Create Medication
      $med = Medicamento::create([
        'nombre' => 'Test Med',
        'descripcion' => 'Test',
        'unidad_medida' => 'Box',
        'unidades' => 1
      ]);

      // Add Inventory
      // A: 0
      Inventario::create([
        'cadena_id' => $sucA->cadena_id,
        'sucursal_id' => $sucA->sucursal_id,
        'medicamento_id' => $med->id,
        'stock_disponible' => 0,
        'precio_unitario' => 100,
        'minimo' => 5,
        'maximo' => 20
      ]);

      // B: 10
      Inventario::create([
        'cadena_id' => $sucB->cadena_id,
        'sucursal_id' => $sucB->sucursal_id,
        'medicamento_id' => $med->id,
        'stock_disponible' => 10,
        'precio_unitario' => 100,
        'minimo' => 5,
        'maximo' => 20
      ]);

      // C: 10
      Inventario::create([
        'cadena_id' => $sucC->cadena_id,
        'sucursal_id' => $sucC->sucursal_id,
        'medicamento_id' => $med->id,
        'stock_disponible' => 10,
        'precio_unitario' => 100,
        'minimo' => 5,
        'maximo' => 20
      ]);

      // 2. Test GeoLocationService directly
      $service = new GeoLocationService();
      $nearest = $service->buscarSucursalesCercanasConStock(
        [$med->id],
        0,
        0, // User location (Branch A)
        $sucAId // Exclude A
      );

      $this->assertCount(2, $nearest);
      $this->assertEquals($sucBId, $nearest->first()->sucursal_id); // B should be first
      $this->assertEquals($sucCId, $nearest->last()->sucursal_id); // C should be last

    } finally {
      DB::rollBack();
    }
  }
}
