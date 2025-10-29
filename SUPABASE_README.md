# 🚀 Integración con Supabase

Esta guía te ayudará a configurar Supabase en tu proyecto Laravel Security Access.

## 📋 Prerrequisitos

1. Cuenta en [Supabase](https://supabase.com)
2. Proyecto creado en Supabase
3. Laravel funcionando correctamente

## ⚙️ Configuración paso a paso

### 1. Crear proyecto en Supabase

1. Ve a [supabase.com/dashboard](https://supabase.com/dashboard)
2. Clic en "New Project"
3. Llena los datos:
    - **Name**: `security-access`
    - **Database Password**: (crea una contraseña segura)
    - **Region**: Selecciona la más cercana
4. Espera a que se cree el proyecto (2-3 minutos)

### 2. Obtener credenciales

1. En tu proyecto, ve a **Settings > API**
2. Copia estas credenciales:
    - **URL**: `https://abc123.supabase.co`
    - **anon public**: `eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...`
    - **service_role secret**: `eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...`

### 3. Configurar en Laravel

Actualiza tu archivo `.env`:

```env
# Supabase Configuration
SUPABASE_URL=https://tu-proyecto.supabase.co
SUPABASE_ANON_KEY=tu-anon-key-aqui
SUPABASE_SERVICE_KEY=tu-service-role-key-aqui
```

### 4. Ejecutar configuración automática

```bash
php artisan supabase:setup
```

Este comando:

-   ✅ Verifica tu configuración
-   ✅ Prueba la conexión
-   ✅ Te da el SQL para crear las tablas

### 5. Crear tablas en Supabase

1. Ve a **SQL Editor** en tu dashboard de Supabase
2. Crea una nueva query
3. Copia y pega el SQL que te dio el comando anterior
4. Ejecuta la query

### 6. Probar la integración

```bash
php artisan supabase:test
```

## 🔄 Migración de datos existentes

Si ya tienes datos en MySQL, puedes migrarlos:

```bash
php artisan supabase:migrate
```

## 🔌 Cambiar a PostgreSQL de Supabase

Para usar Supabase como tu base de datos principal:

1. En tu `.env`, comenta MySQL y descomenta PostgreSQL:

```env
# MySQL (comentado)
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=security_access
# DB_USERNAME=root
# DB_PASSWORD=

# PostgreSQL de Supabase (activo)
DB_CONNECTION=pgsql
DB_HOST=db.tu-proyecto.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=tu-password-de-supabase
```

2. Ejecuta las migraciones:

```bash
php artisan migrate
```

## 🛠️ Comandos disponibles

| Comando                        | Descripción                      |
| ------------------------------ | -------------------------------- |
| `php artisan supabase:setup`   | Configurar y verificar Supabase  |
| `php artisan supabase:test`    | Probar funcionalidades básicas   |
| `php artisan supabase:migrate` | Migrar datos de MySQL a Supabase |

## 🔥 Funcionalidades disponibles

### API REST automática

Supabase genera automáticamente APIs REST para tus tablas.

### Autenticación

```php
$supabase->signUp('email@example.com', 'password123');
$supabase->signIn('email@example.com', 'password123');
```

### CRUD Operations

```php
// Crear
$supabase->insert('users', ['name' => 'Juan', 'email' => 'juan@example.com']);

// Leer
$users = $supabase->select('users', ['active' => true]);

// Actualizar
$supabase->update('users', ['id' => 1], ['name' => 'Juan Carlos']);

// Eliminar
$supabase->delete('users', ['id' => 1]);
```

### Storage de archivos

```php
$supabase->uploadFile('avatars', 'user1.jpg', $file);
$url = $supabase->getPublicUrl('avatars', 'user1.jpg');
```

## 🔒 Seguridad

Supabase incluye Row Level Security (RLS) activado por defecto. Esto significa que necesitas configurar políticas de acceso para que los usuarios puedan acceder a los datos.

### Ejemplo de política básica:

```sql
-- Permitir que los usuarios vean solo sus propios datos
CREATE POLICY "Users can view own data" ON users
    FOR SELECT USING (auth.uid() = id);

-- Permitir actualizaciones solo de datos propios
CREATE POLICY "Users can update own data" ON users
    FOR UPDATE USING (auth.uid() = id);
```

## 🆘 Troubleshooting

### Error de conexión

-   Verifica que las credenciales en `.env` sean correctas
-   Asegúrate de que la URL no termine con `/`
-   Verifica que el proyecto de Supabase esté activo

### Error 401 Unauthorized

-   Revisa que la `anon_key` sea correcta
-   Para operaciones administrativas, usa `service_key`

### Error en migraciones

-   Verifica que las tablas estén creadas en Supabase
-   Asegúrate de que RLS permita las operaciones

## 📚 Recursos adicionales

-   [Documentación de Supabase](https://supabase.com/docs)
-   [Supabase + Laravel Guide](https://supabase.com/docs/guides/getting-started/tutorials/with-laravel)
-   [PostgreSQL Documentation](https://www.postgresql.org/docs/)
