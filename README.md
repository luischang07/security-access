# Security Access

## Acerca de la aplicación

Security Access es una aplicación web de gestión de acceso y seguridad construida con PHP. Esta aplicación proporciona un sistema robusto de autenticación y control de acceso con las siguientes características:

-   **Sistema de autenticación seguro** con verificación por email
-   **Gestión de sesiones únicas** para prevenir múltiples inicios de sesión
-   **Control de bloqueo de cuentas** con sistema de reintento automático
-   **Interfaz de usuario responsive** y moderna
-   **Integración con servicios de email** para notificaciones de seguridad
-   **Dashboard de administración** con métricas de seguridad
-   **Integración con Supabase** para almacenamiento en la nube

## Características principales

-   **Autenticación por email**: Sistema de verificación por correo electrónico para mayor seguridad
-   **Sesión única**: Previene múltiples sesiones activas del mismo usuario
-   **Bloqueo temporal**: Sistema de bloqueo automático tras intentos fallidos de login
-   **Notificaciones**: Emails automáticos para eventos de seguridad importantes
-   **Dashboard moderno**: Interfaz limpia y funcional para administración
-   **API REST**: Integración con servicios externos a través de API

## Instalación

1. Clona el repositorio
2. Instala las dependencias: `composer install`
3. Configura el archivo `.env` con tus credenciales de base de datos
4. Ejecuta las migraciones: `php artisan migrate`
5. Inicia el servidor: `php artisan serve`

## Configuración

La aplicación requiere configuración de:

-   Base de datos (MySQL/PostgreSQL)
-   Servicio de email (Mailtrap.io recomendado para desarrollo)
-   Supabase (opcional, para funcionalidades en la nube)

## Uso

1. Accede a la aplicación a través del navegador
2. Regístrate con tu email
3. Verifica tu cuenta a través del email recibido
4. Inicia sesión y accede al dashboard

## Tecnologías utilizadas

-   **Backend**: PHP con arquitectura MVC
-   **Frontend**: HTML, CSS, JavaScript modular
-   **Base de datos**: MySQL/PostgreSQL
-   **Email**: Integración con proveedores SMTP
-   **Cloud**: Supabase para servicios adicionales

## Licencia

Este proyecto está licenciado bajo la [Licencia MIT](https://opensource.org/licenses/MIT).
