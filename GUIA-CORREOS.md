# 📧 Guía Completa de Configuración de Correos

## ✅ Estado Actual: FUNCIONANDO

-   Sistema de correos configurado con driver 'log'
-   Los emails se guardan en `storage/logs/laravel.log`
-   Comando de prueba: `php artisan test:session-reset [email]`

## 🔧 Opciones de Configuración

### 1️⃣ DESARROLLO (Actual - Log Driver)

```env
MAIL_MAILER=log
MAIL_FROM_ADDRESS="security@tuapp.com"
MAIL_FROM_NAME="Sistema de Seguridad"
```

✅ **Ventajas**: No necesita configuración adicional
📝 **Ver emails en**: `storage/logs/laravel.log`

### 2️⃣ DESARROLLO (MailHog - Recomendado)

```bash
# Instalar MailHog
# Opción 1: Chocolatey
choco install mailhog

# Opción 2: Scoop
scoop install mailhog

# Opción 3: Descarga directa
# https://github.com/mailhog/MailHog/releases

# Ejecutar
mailhog
```

```env
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="security@tuapp.com"
MAIL_FROM_NAME="Sistema de Seguridad"
```

✅ **Ventajas**: Interfaz web bonita
🌐 **Ver emails en**: http://localhost:8025

### 3️⃣ PRODUCCIÓN (Gmail)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@gmail.com
MAIL_PASSWORD=tu-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="tu-email@gmail.com"
MAIL_FROM_NAME="Sistema de Seguridad"
```

📋 **Pasos para Gmail**:

1. Ir a Configuración de Google Account
2. Seguridad > Verificación en 2 pasos (activar)
3. Generar "Contraseña de aplicación"
4. Usar esa contraseña, NO la normal

### 4️⃣ PRODUCCIÓN (Outlook/Hotmail)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp-mail.outlook.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@outlook.com
MAIL_PASSWORD=tu-contraseña-normal
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="tu-email@outlook.com"
MAIL_FROM_NAME="Sistema de Seguridad"
```

### 5️⃣ PRODUCCIÓN (Servicios Profesionales)

Para aplicaciones serias, considera:

-   **SendGrid** (hasta 100 emails/día gratis)
-   **Mailgun** (hasta 5,000 emails/mes gratis)
-   **Amazon SES** (muy económico)
-   **Postmark** (excelente para transaccionales)

## 🧪 Comandos de Prueba

```bash
# Probar con email por defecto
php artisan test:session-reset

# Probar con email específico
php artisan test:session-reset usuario@ejemplo.com

# Limpiar sesiones y tokens expirados
php artisan sessions:clean-expired

# Ver configuración actual de correo
php artisan config:show mail
```

## 🔍 Verificar Funcionamiento

1. **Ejecutar comando de prueba**:

    ```bash
    php artisan test:session-reset
    ```

2. **Verificar email generado**:

    - Log: `storage/logs/laravel.log`
    - MailHog: http://localhost:8025
    - Email real: Revisar bandeja de entrada

3. **Probar flujo completo**:
    - Crear usuario con sesión activa
    - Intentar login desde "otro dispositivo"
    - Ver botón de "Enviar email"
    - Hacer clic en enlace del email
    - Verificar que sesión se elimina

## ⚠️ Problemas Comunes

**Error: "Connection refused"**

-   Verificar que MailHog esté ejecutándose
-   Verificar puerto en configuración

**Gmail: "Authentication failed"**

-   Activar verificación en 2 pasos
-   Generar contraseña de aplicación
-   NO usar contraseña normal

**Emails no llegan**

-   Revisar carpeta de spam
-   Verificar configuración SMTP
-   Probar con comando de prueba primero

## 📋 Checklist Final

-   [ ] Configuración de .env actualizada
-   [ ] Comando de prueba ejecutado exitosamente
-   [ ] Email recibido y enlace funciona
-   [ ] Token se elimina después de usar
-   [ ] Sesión se elimina correctamente
-   [ ] Usuario puede hacer login después del reset

## 🚀 Para Ir a Producción

1. Cambiar a SMTP real (Gmail/Outlook/SendGrid)
2. Configurar dominio real en FROM_ADDRESS
3. Configurar cron job para limpieza automática:
    ```bash
    * * * * * php /path/to/project/artisan schedule:run
    ```
4. Activar HTTPS
5. Configurar rate limiting para envío de emails
