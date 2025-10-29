# 📧 Configuración de Mailtrap.io

## 🚀 Pasos para Configurar Mailtrap

### 1️⃣ Crear Cuenta en Mailtrap.io

1. Ve a https://mailtrap.io
2. Registrate gratuitamente
3. Confirma tu email

### 2️⃣ Configurar para DESARROLLO (Testing)

```bash
# En tu cuenta Mailtrap:
# 1. Ve a "Email Testing" -> "Inboxes"
# 2. Crea un nuevo inbox o usa "My Inbox"
# 3. Ve a "SMTP Settings" -> "Laravel 9+"
# 4. Copia las credenciales
```

**Configurar .env para DESARROLLO:**

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=tu_username_sandbox
MAIL_PASSWORD=tu_password_sandbox
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="security@tuapp.com"
MAIL_FROM_NAME="Sistema de Seguridad"

# API Token (opcional para desarrollo)
MAILTRAP_API_TOKEN=tu_api_token_aqui
MAILTRAP_SANDBOX_INBOX_ID=1234567
```

### 3️⃣ Configurar para PRODUCCIÓN (Sending)

```bash
# En tu cuenta Mailtrap:
# 1. Ve a "Email Sending" -> "Domains"
# 2. Agrega tu dominio (ej: tuapp.com)
# 3. Configura DNS records (SPF, DKIM, DMARC)
# 4. Ve a "SMTP Settings"
# 5. Copia las credenciales de PRODUCCIÓN
```

**Configurar .env para PRODUCCIÓN:**

```env
MAIL_MAILER=smtp
MAIL_HOST=live.smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=api
MAIL_PASSWORD=tu_api_token_sending
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="security@tudominio.com"
MAIL_FROM_NAME="Sistema de Seguridad"

# API Configuration
MAILTRAP_API_TOKEN=tu_api_token_aqui
MAILTRAP_LIVE_DOMAIN=tudominio.com
```

### 4️⃣ Obtener API Token

```bash
# En Mailtrap:
# 1. Ve a "Settings" -> "API Tokens"
# 2. Crea un nuevo token
# 3. Selecciona permisos: "Mail Send" y "Mail Info"
# 4. Copia el token generado
```

## 🧪 Probar la Configuración

### Desarrollo (Sandbox):

```bash
# 1. Configurar credenciales de sandbox en .env
# 2. Probar envío
php artisan test:session-reset test@ejemplo.com

# 3. Ver email en: https://mailtrap.io/inboxes
```

### Producción (Live):

```bash
# 1. Verificar dominio en Mailtrap
# 2. Configurar DNS records
# 3. Cambiar a credenciales live en .env
# 4. Probar con email real
php artisan test:session-reset tu-email@real.com
```

## 📊 Características de Mailtrap

### ✅ Desarrollo (Sandbox):

-   ✅ Emails capturados, no enviados realmente
-   ✅ Interfaz web para ver emails
-   ✅ Análisis de HTML/CSS
-   ✅ Pruebas de spam
-   ✅ Múltiples inboxes
-   ✅ Colaboración en equipo

### ✅ Producción (Sending):

-   ✅ Envío real de emails
-   ✅ Estadísticas detalladas
-   ✅ Bounces y complaints
-   ✅ Templates management
-   ✅ Webhooks
-   ✅ Analytics avanzadas

## 🎯 Ventajas vs Otras Opciones

### 🆚 Gmail/Outlook:

-   ✅ Más profesional
-   ✅ Mejor deliverability
-   ✅ Estadísticas detalladas
-   ✅ No hay límites estrictos
-   ✅ Soporte técnico

### 🆚 SendGrid/Mailgun:

-   ✅ Más fácil configuración
-   ✅ Plan gratuito generoso
-   ✅ Interfaz más amigable
-   ✅ Testing integrado

## 💰 Precios (2025)

### Gratuito:

-   1,000 emails/mes (testing)
-   100 emails/mes (sending)
-   1 dominio
-   Soporte por email

### Paid Plans:

-   Desde $10/mes
-   Más emails y dominios
-   Soporte prioritario
-   Features avanzadas

## 🔧 Troubleshooting

### Error: "Authentication failed"

```bash
# Verificar credenciales en .env
# Regenerar password en Mailtrap
# Confirmar que usas sandbox vs live
```

### Error: "Domain not verified"

```bash
# Solo para producción
# Verificar DNS records
# SPF: v=spf1 include:mailtrap.io ~all
# DKIM: Configurar en panel de Mailtrap
```

### Emails no llegan

```bash
# Desarrollo: Revisar inbox en Mailtrap.io
# Producción: Verificar spam, bounces, analytics
```

## 📋 Checklist Final

-   [ ] Cuenta Mailtrap creada
-   [ ] Credenciales configuradas en .env
-   [ ] API token generado (opcional)
-   [ ] Comando de prueba exitoso
-   [ ] Emails visibles en interface
-   [ ] Para producción: dominio verificado
-   [ ] DNS records configurados (producción)

## 🚀 Comandos Útiles

```bash
# Probar envío
php artisan test:session-reset [email]

# Limpiar cache después de cambios
php artisan config:cache

# Ver estadísticas (con API)
php artisan tinker
>>> app(\App\Services\MailtrapSessionResetService::class)->getStatistics()

# Limpiar tokens expirados
php artisan sessions:clean-expired
```
