<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    $driver = DB::connection()->getDriverName();

    // We use raw SQL for spatial columns as Laravel blueprint support varies

    if ($driver === 'pgsql') {
      $this->upPostgres();
    } elseif ($driver === 'mysql' || $driver === 'mariadb') {
      $this->upMysql();
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    $driver = DB::connection()->getDriverName();

    if ($driver === 'pgsql') {
      $this->downPostgres();
    } elseif ($driver === 'mysql' || $driver === 'mariadb') {
      $this->downMysql();
    }

    Schema::table('sucursales', function (Blueprint $table) {
      $table->dropColumn('location');
    });
  }

  private function upPostgres(): void
  {
    // PostgreSQL / PostGIS
    DB::statement('CREATE EXTENSION IF NOT EXISTS postgis;');
    DB::statement('ALTER TABLE sucursales ADD COLUMN location geography(POINT, 4326);');
    DB::statement('CREATE INDEX sucursales_location_index ON sucursales USING GIST (location);');
    DB::statement('UPDATE sucursales SET location = ST_SetSRID(ST_MakePoint(longitud, latitud), 4326)::geography WHERE latitud IS NOT NULL AND longitud IS NOT NULL;');

    // Trigger to keep location in sync
    DB::statement("
        CREATE OR REPLACE FUNCTION update_sucursales_location() RETURNS TRIGGER AS $$
        BEGIN
            IF NEW.latitud IS NOT NULL AND NEW.longitud IS NOT NULL THEN
                NEW.location = ST_SetSRID(ST_MakePoint(NEW.longitud, NEW.latitud), 4326)::geography;
            END IF;
            RETURN NEW;
        END;
        $$ LANGUAGE plpgsql;
      ");

    DB::statement("
        CREATE TRIGGER set_sucursales_location
        BEFORE INSERT OR UPDATE ON sucursales
        FOR EACH ROW EXECUTE FUNCTION update_sucursales_location();
      ");
  }

  private function upMysql(): void
  {
    // MySQL / MariaDB
    DB::statement('ALTER TABLE sucursales ADD COLUMN location POINT;');

    try {
      DB::statement('CREATE SPATIAL INDEX sucursales_location_index ON sucursales (location);');
    } catch (\Exception $e) {
      // Ignore if not supported or index creation fails
    }

    // MariaDB 10.4 no soporta 'SRID 4326' en la definición de la columna, pero podemos guardar los datos con el SRID correcto.
    DB::statement('UPDATE sucursales SET location = ST_GeomFromText(CONCAT("POINT(", longitud, " ", latitud, ")"), 4326) WHERE latitud IS NOT NULL AND longitud IS NOT NULL;');

    // Triggers to keep location in sync
    DB::unprepared('
        CREATE TRIGGER sucursales_location_insert BEFORE INSERT ON sucursales FOR EACH ROW
        BEGIN
          IF NEW.latitud IS NOT NULL AND NEW.longitud IS NOT NULL THEN
            SET NEW.location = ST_GeomFromText(CONCAT("POINT(", NEW.longitud, " ", NEW.latitud, ")"), 4326);
          END IF;
        END
      ');

    DB::unprepared('
        CREATE TRIGGER sucursales_location_update BEFORE UPDATE ON sucursales FOR EACH ROW
        BEGIN
          IF NEW.latitud IS NOT NULL AND NEW.longitud IS NOT NULL THEN
            SET NEW.location = ST_GeomFromText(CONCAT("POINT(", NEW.longitud, " ", NEW.latitud, ")"), 4326);
          END IF;
        END
      ');
  }

  private function downPostgres(): void
  {
    DB::statement("DROP TRIGGER IF EXISTS set_sucursales_location ON sucursales");
    DB::statement("DROP FUNCTION IF EXISTS update_sucursales_location");
  }

  private function downMysql(): void
  {
    DB::unprepared("DROP TRIGGER IF EXISTS sucursales_location_insert");
    DB::unprepared("DROP TRIGGER IF EXISTS sucursales_location_update");
  }
};
