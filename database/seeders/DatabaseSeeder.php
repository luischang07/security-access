<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
  use WithoutModelEvents;

  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    // Create Users
    User::factory(10)->create();

    // Create specific test users
    $adminUser = User::factory()->create([
      'nombre' => 'Admin User',
      'correo' => 'admin@example.com',
      'direccion' => '123 Admin St',
      'password' => Hash::make('password'),
      'nip' => '1234',
    ]);

    $pacienteUser1 = User::factory()->create([
      'nombre' => 'Juan Pérez',
      'correo' => 'juan@example.com',
      'direccion' => '456 Patient Ave',
      'password' => Hash::make('password'),
      'nip' => '4567',
    ]);

    $pacienteUser2 = User::factory()->create([
      'nombre' => 'María García',
      'correo' => 'maria@example.com',
      'direccion' => '789 Health Blvd',
      'password' => Hash::make('password'),
      'nip' => '7890',
    ]);

    $empleadoUser1 = User::factory()->create([
      'nombre' => 'Pedro Farmacéutico',
      'correo' => 'pedro@example.com',
      'direccion' => '321 Employee Rd',
      'password' => Hash::make('password'),
      'nip' => '3210',
    ]);

    // Create Administradores
    DB::table('administradores')->insert([
      'user_id' => $adminUser->user_id,
      'rol_admin' => 'super',
      'ultimo_login' => now(),
    ]);

    // Create Pacientes
    DB::table('pacientes')->insert([
      ['user_id' => $pacienteUser1->user_id, 'monto_penalizacion' => 0],
      ['user_id' => $pacienteUser2->user_id, 'monto_penalizacion' => 50.00],
    ]);

    // Create Cadena Farmaceuticas
    $cadena1 = DB::table('cadena_farmaceuticas')->insertGetId([
      'razon_social' => 'Farmacias del Ahorro SA',
      'name' => 'Del Ahorro',
    ], 'cadena_id');

    $cadena2 = DB::table('cadena_farmaceuticas')->insertGetId([
      'razon_social' => 'Farmacias Guadalajara SA',
      'name' => 'Guadalajara',
    ], 'cadena_id');

    $cadena3 = DB::table('cadena_farmaceuticas')->insertGetId([
      'razon_social' => 'Farmacias Similares SA',
      'name' => 'Similares',
    ], 'cadena_id');

    // Create Sucursales
    DB::table('sucursales')->insert([
      ['cadena_id' => $cadena1, 'sucursal_id' => 1, 'nombre' => 'Del Ahorro Centro', 'direccion' => 'Av. Principal 123', 'latitud' => 19.4326, 'longitud' => -99.1332],
      ['cadena_id' => $cadena1, 'sucursal_id' => 2, 'nombre' => 'Del Ahorro Norte', 'direccion' => 'Calle Norte 456', 'latitud' => 19.4500, 'longitud' => -99.1500],
      ['cadena_id' => $cadena2, 'sucursal_id' => 1, 'nombre' => 'Guadalajara Sur', 'direccion' => 'Blvd. Sur 789', 'latitud' => 19.4000, 'longitud' => -99.1200],
      ['cadena_id' => $cadena2, 'sucursal_id' => 2, 'nombre' => 'Guadalajara Este', 'direccion' => 'Av. Este 321', 'latitud' => 19.4200, 'longitud' => -99.1000],
      ['cadena_id' => $cadena3, 'sucursal_id' => 1, 'nombre' => 'Similares Oeste', 'direccion' => 'Calle Oeste 654', 'latitud' => 19.4100, 'longitud' => -99.1600],
    ]);

    // Create Empleados
    DB::table('empleados')->insert([
      'user_id' => $empleadoUser1->user_id,
      'cadena_id' => $cadena1,
      'sucursal_id' => 1,
      'fecha_ingreso' => now()->subYears(2),
    ]);

    // Create Medicamentos
    $medicamentos = [
      ['nombre' => 'Paracetamol 500mg', 'descripcion' => 'Analgésico y antipirético', 'unidad_medida' => 'mg', 'unidades' => 'comprimidos'],
      ['nombre' => 'Ibuprofeno 400mg', 'descripcion' => 'Antiinflamatorio no esteroideo', 'unidad_medida' => 'mg', 'unidades' => 'comprimidos'],
      ['nombre' => 'Amoxicilina 500mg', 'descripcion' => 'Antibiótico de amplio espectro', 'unidad_medida' => 'mg', 'unidades' => 'cápsulas'],
      ['nombre' => 'Omeprazol 20mg', 'descripcion' => 'Inhibidor de bomba de protones', 'unidad_medida' => 'mg', 'unidades' => 'cápsulas'],
      ['nombre' => 'Losartán 50mg', 'descripcion' => 'Antihipertensivo', 'unidad_medida' => 'mg', 'unidades' => 'comprimidos'],
      ['nombre' => 'Metformina 850mg', 'descripcion' => 'Antidiabético oral', 'unidad_medida' => 'mg', 'unidades' => 'comprimidos'],
      ['nombre' => 'Atorvastatina 20mg', 'descripcion' => 'Hipolipemiante', 'unidad_medida' => 'mg', 'unidades' => 'comprimidos'],
      ['nombre' => 'Aspirina 100mg', 'descripcion' => 'Antiagregante plaquetario', 'unidad_medida' => 'mg', 'unidades' => 'comprimidos'],
    ];

    foreach ($medicamentos as $med) {
      DB::table('medicamentos')->insert($med);
    }

    // Create Inventarios
    $sucursales = DB::table('sucursales')->get();
    $medicamentosIds = DB::table('medicamentos')->pluck('id');

    foreach ($sucursales as $sucursal) {
      foreach ($medicamentosIds as $medId) {
        DB::table('inventarios')->insert([
          'cadena_id' => $sucursal->cadena_id,
          'sucursal_id' => $sucursal->sucursal_id,
          'medicamento_id' => $medId,
          'cantidad' => rand(10, 200),
        ]);
      }
    }

    // Create Pedidos
    $pedido1 = DB::table('pedidos')->insertGetId([
      'paciente_id' => $pacienteUser1->user_id,
      'cadena_id' => $cadena1,
      'sucursal_id' => 1,
      'fecha_pedido' => now()->subDays(5),
      'fecha_entrega' => now()->subDays(2),
      'estado' => 'completado',
      'costo_total' => 250.50,
    ], 'pedido_id');

    $pedido2 = DB::table('pedidos')->insertGetId([
      'paciente_id' => $pacienteUser2->user_id,
      'cadena_id' => $cadena2,
      'sucursal_id' => 1,
      'fecha_pedido' => now()->subDay(),
      'fecha_entrega' => null,
      'estado' => 'en_proceso',
      'costo_total' => 180.00,
    ], 'pedido_id');

    // Create Lineas Pedidos
    DB::table('lineas_pedidos')->insert([
      ['pedido_id' => $pedido1, 'linea_id' => 1, 'medicamento_id' => 1, 'cantidad_solicitada' => 2, 'precio_unitario' => 50.00],
      ['pedido_id' => $pedido1, 'linea_id' => 2, 'medicamento_id' => 2, 'cantidad_solicitada' => 3, 'precio_unitario' => 75.00],
      ['pedido_id' => $pedido2, 'linea_id' => 1, 'medicamento_id' => 3, 'cantidad_solicitada' => 1, 'precio_unitario' => 180.00],
    ]);

    // Create Detalle Lineas Pedidos
    DB::table('detalle_lineas_pedidos')->insert([
      ['pedido_id' => $pedido1, 'linea_id' => 1, 'cadena_id' => $cadena1, 'sucursal_id' => 1, 'cantidad_asignada' => 2, 'cantidad_recolectada' => 2],
      ['pedido_id' => $pedido1, 'linea_id' => 2, 'cadena_id' => $cadena1, 'sucursal_id' => 1, 'cantidad_asignada' => 3, 'cantidad_recolectada' => 3],
      ['pedido_id' => $pedido2, 'linea_id' => 1, 'cadena_id' => $cadena2, 'sucursal_id' => 1, 'cantidad_asignada' => 1, 'cantidad_recolectada' => 0],
    ]);

    // Create Ruta Recoleccion
    DB::table('ruta_recoleccion')->insert([
      ['pedido_id' => $pedido1, 'cadena_id' => $cadena1, 'sucursal_id' => 1, 'orden_visita' => 1, 'estado_recoleccion' => 'completado', 'fecha_hora_visita' => now()->subDays(2)],
      ['pedido_id' => $pedido2, 'cadena_id' => $cadena2, 'sucursal_id' => 1, 'orden_visita' => 1, 'estado_recoleccion' => 'pendiente', 'fecha_hora_visita' => null],
    ]);

    // Create Notificaciones
    DB::table('notificaciones')->insert([
      ['user_id' => $pacienteUser1->user_id, 'tipo' => 'pedido', 'mensaje' => 'Su pedido ha sido completado', 'fecha_hora' => now()->subDays(2), 'leida' => true],
      ['user_id' => $pacienteUser2->user_id, 'tipo' => 'penalizacion', 'mensaje' => 'Tiene una penalización pendiente de $50.00', 'fecha_hora' => now()->subDay(), 'leida' => false],
      ['user_id' => $pacienteUser2->user_id, 'tipo' => 'pedido', 'mensaje' => 'Su pedido está en proceso', 'fecha_hora' => now()->subDay(), 'leida' => false],
      ['user_id' => $adminUser->user_id, 'tipo' => 'sistema', 'mensaje' => 'Nuevo pedido registrado en el sistema', 'fecha_hora' => now()->subDay(), 'leida' => true],
    ]);
  }
}
