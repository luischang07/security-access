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
    // $this->call([
    //   PenaltySeeder::class,
    // ]);

    // Create specific test users
    $adminUser = User::factory()->create([
      'nombre' => 'Admin',
      'apellido' => 'User',
      'correo' => 'admin@example.com',
      'password' => Hash::make('Admin123!'),
    ]);

    $pacienteUser1 = User::factory()->create([
      'nombre' => 'Juan',
      'apellido' => 'Perez',
      'correo' => 'juan@example.com',
      'password' => Hash::make('Patient123!'),
    ]);

    $pacienteUser2 = User::factory()->create([
      'nombre' => 'María',
      'apellido' => 'Perez',
      'correo' => 'maria@example.com',
      'password' => Hash::make('Maria123!'),
    ]);

    $empleadoUser1 = User::factory()->create([
      'nombre' => 'Pedro',
      'apellido' => 'Perez',
      'correo' => 'pedro@example.com',
      'password' => Hash::make('Pharmacy123!'),
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
    $cadena1 = 'CAD001';
    DB::table('cadena_farmaceuticas')->insert([
      'cadena_id' => $cadena1,
      'razon_social' => 'Farmacias del Ahorro SA',
      'nombre' => 'Del Ahorro',
    ]);

    $cadena2 = 'CAD002';
    DB::table('cadena_farmaceuticas')->insert([
      'cadena_id' => $cadena2,
      'razon_social' => 'Farmacias Guadalajara SA',
      'nombre' => 'Guadalajara',
    ]);

    $cadena3 = 'CAD003';
    DB::table('cadena_farmaceuticas')->insert([
      'cadena_id' => $cadena3,
      'razon_social' => 'Farmacias Similares SA',
      'nombre' => 'Similares',
    ]);


    // Create Sucursales
    DB::table('sucursales')->insert([
      ['cadena_id' => $cadena1, 'sucursal_id' => 'SUC001', 'nombre' => 'Del Ahorro Hermanas', 'calle' => 'Boulevard Ciudades Hermanas', 'numero_ext' => '75', 'numero_int' => null, 'colonia' => 'Guadalupe', 'latitud' => 24.79139552895991, 'longitud' => -107.39312697344862],
      ['cadena_id' => $cadena1, 'sucursal_id' => 'SUC002', 'nombre' => 'Del Ahorro Colinas', 'calle' => 'Prolongacion Alvaro Obregon', 'numero_ext' => '2891', 'numero_int' => null, 'colonia' => 'Montebello', 'latitud' => 24.78041311629486, 'longitud' => -107.39407371994207],
      ['cadena_id' => $cadena2, 'sucursal_id' => 'SUC001', 'nombre' => 'Guadalajara Hermanas', 'calle' => 'Boulevard Ciudades Hermanas', 'numero_ext' => '75', 'numero_int' => null, 'colonia' => 'Guadalupe', 'latitud' => 24.791902011632253, 'longitud' => -107.3926441758417],
      ['cadena_id' => $cadena2, 'sucursal_id' => 'SUC002', 'nombre' => 'Guadalajara Bravo', 'calle' => 'Gral. Ignacio Ramirez', 'numero_ext' => '768', 'numero_int' => null, 'colonia' => '', 'latitud' => 24.797952074443508, 'longitud' => -107.40166906569905],
      ['cadena_id' => $cadena3, 'sucursal_id' => 'SUC001', 'nombre' => 'Similares Constitucion #35', 'calle' => 'Av. Nicolas Bravo', 'numero_ext' => '654', 'numero_int' => null, 'colonia' => 'Oeste', 'latitud' => 19.4100, 'longitud' => -99.1600],
      ['cadena_id' => $cadena3, 'sucursal_id' => 'SUC002', 'nombre' => 'Similares Bravo', 'calle' => 'Av. Nicolas Bravo', 'numero_ext' => '1578', 'numero_int' => null, 'colonia' => 'Morelos', 'latitud' => 24.788794, 'longitud' => -107.400675],
    ]);

    // Create Empleados
    DB::table('empleados')->insert([
      'user_id' => $empleadoUser1->user_id,
      'cadena_id' => $cadena1,
      'sucursal_id' => 'SUC001',
      'fecha_ingreso' => now()->subYears(2),
    ]);

    // Create Medicamentos
    $medicamentos = [
      ['nombre' => 'Paracetamol 500mg', 'descripcion' => 'Analgésico y antipirético', 'unidad_medida' => 'mg', 'unidades' => '20 tabletas'],
      ['nombre' => 'Ibuprofeno 400mg', 'descripcion' => 'Antiinflamatorio no esteroideo', 'unidad_medida' => 'mg', 'unidades' => '20 tabletas'],
      ['nombre' => 'Amoxicilina 500mg', 'descripcion' => 'Antibiótico de amplio espectro', 'unidad_medida' => 'mg', 'unidades' => '20 tabletas'],
      ['nombre' => 'Omeprazol 20mg', 'descripcion' => 'Inhibidor de bomba de protones', 'unidad_medida' => 'mg', 'unidades' => '20 tabletas'],
      ['nombre' => 'Losartán 50mg', 'descripcion' => 'Antihipertensivo', 'unidad_medida' => 'mg', 'unidades' => '20 tabletas'],
      ['nombre' => 'Metformina 850mg', 'descripcion' => 'Antidiabético oral', 'unidad_medida' => 'mg', 'unidades' => '20 tabletas'],
      ['nombre' => 'Atorvastatina 20mg', 'descripcion' => 'Hipolipemiante', 'unidad_medida' => 'mg', 'unidades' => '20 tabletas'],
      ['nombre' => 'Aspirina 100mg', 'descripcion' => 'Antiagregante plaquetario', 'unidad_medida' => 'mg', 'unidades' => '20 tabletas'],
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
          'minimo' => rand(5, 20),
          'maximo' => rand(5, 20),
          'precio_unitario' => rand(10, 1000),
          'stock_disponible' => rand(5, 20),
        ]);
      }
    }

    // Create Pedidos
    // Create Pedidos
    $pedido1 = 'PED001';
    DB::table('pedidos')->insert([
      'folio_pedido' => $pedido1,
      'cadena_id' => $cadena1,
      'sucursal_id' => 'SUC001',
      'paciente_id' => $pacienteUser1->user_id,
      'cedula_profesional' => '12345678',
      'fecha_pedido' => now()->subDays(5),
      'fecha_entrega' => now()->subDays(2),
      'estatus' => 'completado',
      'costo_total' => 250.50,
    ]);

    $pedido2 = 'PED002';
    DB::table('pedidos')->insert([
      'folio_pedido' => $pedido2,
      'cadena_id' => $cadena2,
      'sucursal_id' => 'SUC001',
      'paciente_id' => $pacienteUser2->user_id,
      'cedula_profesional' => '12345678',
      'fecha_pedido' => now()->subDay(),
      'fecha_entrega' => null,
      'estatus' => 'en_proceso',
      'costo_total' => 180.00,
    ]);

    // Create Lineas Pedidos
    DB::table('lineas_pedidos')->insert([
      ['folio_pedido' => $pedido1, 'id_linea_pedido' => 1, 'medicamento_id' => 1, 'precio_unitario' => 50.00],
      ['folio_pedido' => $pedido1, 'id_linea_pedido' => 2, 'medicamento_id' => 2, 'precio_unitario' => 75.00],
      ['folio_pedido' => $pedido2, 'id_linea_pedido' => 1, 'medicamento_id' => 3, 'precio_unitario' => 180.00],
    ]);

    // Create Detalle Lineas Pedidos
    DB::table('detalle_lineas_pedidos')->insert([
      ['folio_pedido' => $pedido1, 'id_linea_pedido' => 1, 'cadena_id' => $cadena1, 'sucursal_id' => 'SUC001', 'cantidad_surtida' => 2, 'precio_unitario' => 50.00],
      ['folio_pedido' => $pedido1, 'id_linea_pedido' => 2, 'cadena_id' => $cadena1, 'sucursal_id' => 'SUC001', 'cantidad_surtida' => 3, 'precio_unitario' => 75.00],
      ['folio_pedido' => $pedido2, 'id_linea_pedido' => 1, 'cadena_id' => $cadena2, 'sucursal_id' => 'SUC001', 'cantidad_surtida' => 0, 'precio_unitario' => 180.00],
    ]);

    // Create Ruta Recoleccion
    DB::table('ruta_recoleccion')->insert([
      ['folio_pedido' => $pedido1, 'cadena_id' => $cadena1, 'sucursal_id' => 'SUC001', 'orden_recoleccion' => 1],
      ['folio_pedido' => $pedido2, 'cadena_id' => $cadena2, 'sucursal_id' => 'SUC001', 'orden_recoleccion' => 1],
    ]);

    // Create Notificaciones
    DB::table('notificaciones')->insert([
      ['user_id' => $pacienteUser1->user_id, 'mensaje' => 'Su pedido ha sido completado', 'fecha_hora' => now()->subDays(2), 'leida' => true],
      ['user_id' => $pacienteUser2->user_id, 'mensaje' => 'Tiene una penalización pendiente de $50.00', 'fecha_hora' => now()->subDay(), 'leida' => false],
      ['user_id' => $pacienteUser2->user_id, 'mensaje' => 'Su pedido está en proceso', 'fecha_hora' => now()->subDay(), 'leida' => false],
      ['user_id' => $adminUser->user_id, 'mensaje' => 'Nuevo pedido registrado en el sistema', 'fecha_hora' => now()->subDay(), 'leida' => true],
    ]);

    // Run Penalty Seeder after patients are created
    $this->call([
      PenaltySeeder::class,
    ]);
  }
}
