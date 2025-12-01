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
      ['cadena_id' => $cadena1, 'sucursal_id' => 'SUC001', 'nombre' => 'Del Ahorro Hermanas', 'calle' => 'Boulevard Ciudades Hermanas', 'numero_ext' => '75', 'numero_int' => null, 'colonia' => 'Guadalupe', 'latitud' => 24.79139552895991, 'longitud' => -107.39312697344862, 'contacto' => '+52 667 123 4567'],
      ['cadena_id' => $cadena1, 'sucursal_id' => 'SUC002', 'nombre' => 'Del Ahorro Colinas', 'calle' => 'Prolongacion Alvaro Obregon', 'numero_ext' => '2891', 'numero_int' => null, 'colonia' => 'Montebello', 'latitud' => 24.78041311629486, 'longitud' => -107.39407371994207, 'contacto' => '+52 667 234 5678'],
      ['cadena_id' => $cadena2, 'sucursal_id' => 'SUC001', 'nombre' => 'Guadalajara Hermanas', 'calle' => 'Boulevard Ciudades Hermanas', 'numero_ext' => '75', 'numero_int' => null, 'colonia' => 'Guadalupe', 'latitud' => 24.791902011632253, 'longitud' => -107.3926441758417, 'contacto' => '+52 667 345 6789'],
      ['cadena_id' => $cadena2, 'sucursal_id' => 'SUC002', 'nombre' => 'Guadalajara Bravo', 'calle' => 'Gral. Ignacio Ramirez', 'numero_ext' => '768', 'numero_int' => null, 'colonia' => '', 'latitud' => 24.797952074443508, 'longitud' => -107.40166906569905, 'contacto' => '+52 667 456 7890'],
      ['cadena_id' => $cadena3, 'sucursal_id' => 'SUC001', 'nombre' => 'Similares Constitucion #35', 'calle' => 'Av. Nicolas Bravo', 'numero_ext' => '654', 'numero_int' => null, 'colonia' => 'Oeste', 'latitud' => 19.4100, 'longitud' => -99.1600, 'contacto' => '+52 555 567 8901'],
      ['cadena_id' => $cadena3, 'sucursal_id' => 'SUC002', 'nombre' => 'Similares Bravo', 'calle' => 'Av. Nicolas Bravo', 'numero_ext' => '1578', 'numero_int' => null, 'colonia' => 'Morelos', 'latitud' => 24.788794, 'longitud' => -107.400675, 'contacto' => '+52 667 678 9012'],
    ]);

    // Create Sucursal Horarios
    $sucursales = DB::table('sucursales')->get();
    foreach ($sucursales as $sucursal) {
      // Weekdays (Mon-Fri)
      for ($day = 1; $day <= 5; $day++) {
        DB::table('sucursal_horarios')->insert([
          'cadena_id' => $sucursal->cadena_id,
          'sucursal_id' => $sucursal->sucursal_id,
          'dia_semana' => $day,
          'hora_apertura' => '08:00:00',
          'hora_cierre' => '21:00:00',
          'es_cerrado' => false,
        ]);
      }
      // Saturday
      DB::table('sucursal_horarios')->insert([
        'cadena_id' => $sucursal->cadena_id,
        'sucursal_id' => $sucursal->sucursal_id,
        'dia_semana' => 6,
        'hora_apertura' => '09:00:00',
        'hora_cierre' => '18:00:00',
        'es_cerrado' => false,
      ]);
      // Sunday
      DB::table('sucursal_horarios')->insert([
        'cadena_id' => $sucursal->cadena_id,
        'sucursal_id' => $sucursal->sucursal_id,
        'dia_semana' => 0,
        'hora_apertura' => '09:00:00',
        'hora_cierre' => '18:00:00',
        'es_cerrado' => false,
      ]);
    }

    // Create Empleados
    DB::table('empleados')->insert([
      'user_id' => $empleadoUser1->user_id,
      'cadena_id' => $cadena1,
      'sucursal_id' => 'SUC001',
      'fecha_ingreso' => now()->subYears(2),
    ]);

    // Create Medicamentos
    $medicamentos = [
      ['nombre' => 'Paracetamol 500mg', 'descripcion' => 'Analgésico y antipirético', 'unidad_medida' => 'mg', 'unidades' => 20],
      ['nombre' => 'Ibuprofeno 400mg', 'descripcion' => 'Antiinflamatorio no esteroideo', 'unidad_medida' => 'mg', 'unidades' => 20],
      ['nombre' => 'Amoxicilina 500mg', 'descripcion' => 'Antibiótico de amplio espectro', 'unidad_medida' => 'mg', 'unidades' => 20],
      ['nombre' => 'Omeprazol 20mg', 'descripcion' => 'Inhibidor de bomba de protones', 'unidad_medida' => 'mg', 'unidades' => 20],
      ['nombre' => 'Losartán 50mg', 'descripcion' => 'Antihipertensivo', 'unidad_medida' => 'mg', 'unidades' => 20],
      ['nombre' => 'Metformina 850mg', 'descripcion' => 'Antidiabético oral', 'unidad_medida' => 'mg', 'unidades' => 20],
      ['nombre' => 'Atorvastatina 20mg', 'descripcion' => 'Hipolipemiante', 'unidad_medida' => 'mg', 'unidades' => 20],
      ['nombre' => 'Aspirina 100mg', 'descripcion' => 'Antiagregante plaquetario', 'unidad_medida' => 'mg', 'unidades' => 20],
    ];

    foreach ($medicamentos as $med) {
      DB::table('medicamentos')->insert($med);
    }

    // Create Inventarios
    $sucursales = DB::table('sucursales')->get();
    $medicamentosIds = DB::table('medicamentos')->pluck('id');

    foreach ($sucursales as $sucursal) {
      foreach ($medicamentosIds as $medId) {
        $minimo = rand(5, 20);
        DB::table('inventarios')->insert([
          'cadena_id' => $sucursal->cadena_id,
          'sucursal_id' => $sucursal->sucursal_id,
          'medicamento_id' => $medId,
          'minimo' => $minimo,
          'maximo' => rand($minimo + 1, 30),
          'precio_unitario' => rand(10, 1000),
          'stock_disponible' => rand(5, 20),
        ]);
      }
    }

    // Create Pedidos
    // Create Pedidos
    $pedido1 = 1;
    DB::table('pedidos')->insert([
      'folio_pedido' => $pedido1,
      'cadena_id' => $cadena1,
      'sucursal_id' => 'SUC001',
      'paciente_id' => $pacienteUser1->user_id,
      'cedula_profesional' => '12345678',
      'fecha_pedido' => now()->subDays(5),
      'estatus' => 'completado',
      'costo_total' => 250.50,
    ]);

    $pedido2 = 2;
    DB::table('pedidos')->insert([
      'folio_pedido' => $pedido2,
      'cadena_id' => $cadena2,
      'sucursal_id' => 'SUC001',
      'paciente_id' => $pacienteUser2->user_id,
      'cedula_profesional' => '12345678',
      'fecha_pedido' => now()->subDay(),
      'estatus' => 'confirmado',
      'costo_total' => 180.00,
    ]);

    // Create Lineas Pedidos
    DB::table('lineas_pedidos')->insert([
      ['folio_pedido' => $pedido1, 'medicamento_id' => 1, 'cantidad' => 50],
      ['folio_pedido' => $pedido1, 'medicamento_id' => 2, 'cantidad' => 75],
      ['folio_pedido' => $pedido2, 'medicamento_id' => 3, 'cantidad' => 180],
    ]);

    // Create Detalle Lineas Pedidos
    DB::table('detalle_lineas_pedidos')->insert([
      ['folio_pedido' => $pedido1, 'medicamento_id' => 1, 'cadena_id' => $cadena1, 'sucursal_id' => 'SUC001', 'cantidad_surtida' => 2, 'precio_unitario' => 50.00],
      ['folio_pedido' => $pedido1, 'medicamento_id' => 2, 'cadena_id' => $cadena1, 'sucursal_id' => 'SUC001', 'cantidad_surtida' => 3, 'precio_unitario' => 75.00],
      ['folio_pedido' => $pedido2, 'medicamento_id' => 3, 'cadena_id' => $cadena2, 'sucursal_id' => 'SUC001', 'cantidad_surtida' => 0, 'precio_unitario' => 180.00],
    ]);

    // Create Ruta Recoleccion
    DB::table('ruta_recoleccion')->insert([
      ['folio_pedido' => $pedido1, 'cadena_id' => $cadena1, 'sucursal_id' => 'SUC001', 'orden_recoleccion' => 1],
      ['folio_pedido' => $pedido2, 'cadena_id' => $cadena2, 'sucursal_id' => 'SUC001', 'orden_recoleccion' => 1],
    ]);

    // Create Notificaciones
    DB::table('notificaciones')->insert([
      ['folio_pedido' => $pedido1, 'user_id' => $pacienteUser1->user_id, 'mensaje' => 'Su pedido ha sido completado', 'fecha_hora' => now()->subDays(2), 'leida' => true],
      ['folio_pedido' => $pedido2, 'user_id' => $pacienteUser2->user_id, 'mensaje' => 'Tiene una penalización pendiente de $50.00', 'fecha_hora' => now()->subDay(), 'leida' => false],
      ['folio_pedido' => $pedido2, 'user_id' => $pacienteUser2->user_id, 'mensaje' => 'Su pedido está en proceso', 'fecha_hora' => now()->subDay(), 'leida' => false],
      ['folio_pedido' => $pedido1, 'user_id' => $adminUser->user_id, 'mensaje' => 'Nuevo pedido registrado en el sistema', 'fecha_hora' => now()->subDay(), 'leida' => true],
    ]);

    // Run Penalty Seeder after patients are created
    $this->call([
      PenaltySeeder::class,
    ]);
  }
}
