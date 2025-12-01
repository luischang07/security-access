# Resumen de Implementación: Algoritmo Híbrido de Surtido

Este documento detalla la implementación del "Algoritmo Híbrido de Surtido" para la optimización de rutas y surtido distribuido de recetas en la plataforma "Te Acerco Salud".

## 1. Resumen del Objetivo
El objetivo principal fue habilitar la capacidad de localizar sucursales cercanas que tengan stock de medicamentos faltantes en un pedido, utilizando cálculos geoespaciales precisos y soportando tanto MySQL como PostgreSQL.

## 2. Cambios Realizados

### Base de Datos
- **Nueva Migración**: `2025_11_29_000000_enable_postgis_and_add_location_to_sucursales.php`
    - Agrega una columna `location` de tipo espacial a la tabla `sucursales`.
    - **Soporte Dual**: Detecta automáticamente el motor de base de datos:
        - **PostgreSQL**: Habilita la extensión `postgis` y usa tipos `geography`.
        - **MySQL/MariaDB**: Usa tipos `POINT` nativos e índices espaciales (`SPATIAL INDEX`).
    - Migra automáticamente los datos existentes de las columnas `latitud` y `longitud` a la nueva columna `location`.

### Modelos
- **`App\Models\Sucursal`**: Se agregó `location` a la lista de campos asignables (`$fillable`).
- **`App\Models\Inventario`**: Se agregaron `minimo` y `maximo` a `$fillable` para permitir la creación correcta de inventarios.
- **`App\Models\CadenaFarmaceutica`**: Se agregó `cadena_id` a `$fillable` para permitir la inserción manual de IDs (necesario para pruebas y flexibilidad).

### Servicios
- **Nuevo Servicio: `App\Services\GeoLocationService`**
    - Encapsula la lógica geoespacial.
    - **`calculateDistance($lat1, $lng1, $lat2, $lng2)`**: Calcula la distancia en metros entre dos coordenadas. Usa `ST_DistanceSphere` (PostGIS) o `ST_Distance_Sphere` (MySQL).
    - **`findNearestBranchesWithStock($medicamentoIds, $userLat, $userLng, $excludeBranchId)`**: Encuentra las sucursales más cercanas que tienen stock de *al menos uno* de los medicamentos solicitados, excluyendo una sucursal específica (por ejemplo, la sucursal origen sin stock).

- **Modificación: `App\Services\Modelos\GestorDeSurtido`**
    - Se inyectó el `GeoLocationService`.
    - En el método `surtir`, si faltan medicamentos (`$this->SinStock`), se invoca al servicio de geolocalización para encontrar sucursales alternativas cercanas y continuar con el proceso de surtido distribuido.

## 3. Verificación y Pruebas
- **Test Automatizado**: `tests/Feature/DistributedFulfillmentTest.php`
    - Verifica que el sistema pueda:
        1.  Crear cadenas y sucursales con coordenadas geográficas.
        2.  Asignar inventario a estas sucursales.
        3.  Identificar correctamente la sucursal más cercana con stock disponible utilizando el `GeoLocationService`.
    - El test fue ejecutado exitosamente en el entorno local (MySQL).

## 4. Notas Técnicas
- La implementación es agnóstica a la base de datos en cuanto a las funciones espaciales básicas requeridas.
- Para ejecutar los tests en un entorno local con MySQL, asegúrese de que su archivo `phpunit.xml` o `.env.testing` apunte a una base de datos MySQL real, ya que SQLite en memoria no soporta las funciones espaciales específicas utilizadas.
