<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminación de Sesión Activa</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
    <style>
        {!! file_get_contents(resource_path('css/email-session-reset-mailtrap.css')) !!}
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>🔐 Eliminación de Sesión</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Hola {{ $user->getName() }},
            </div>

            <div class="message">
                Hemos detectado que intentaste iniciar sesión desde un <strong>nuevo dispositivo</strong>,
                pero ya tienes una sesión activa en otro dispositivo.
            </div>

            <div class="message">
                Si deseas cerrar tu sesión actual para poder iniciar sesión desde el nuevo dispositivo,
                haz clic en el siguiente botón:
            </div>

            <!-- Call to Action -->
            <div class="cta-container">
                <a href="{{ $url }}" class="cta-button">
                    🗑️ Eliminar Sesión Activa
                </a>
            </div>

            <!-- Warning Box -->
            <div class="warning-box">
                <div class="warning-title">
                    ⚠️ Información Importante
                </div>
                <ul class="warning-list">
                    <li>Al hacer clic, se cerrará tu sesión en <strong>todos los dispositivos</strong></li>
                    <li>Tendrás que volver a iniciar sesión</li>
                    <li>Este enlace es válido por <strong>{{ $expiresIn }} minutos</strong></li>
                    <li>Si no solicitaste esto, puedes ignorar este correo</li>
                </ul>
            </div>

            <!-- Stats (only visible in development) -->
            @if (config('app.debug'))
                <div class="stats">
                    📊 <strong>Información de desarrollo:</strong><br>
                    Usuario ID: {{ $user->getId() }}<br>
                    Timestamp: {{ now()->format('Y-m-d H:i:s') }}<br>
                    Entorno: {{ config('app.env') }}
                </div>
            @endif

            <div class="message">
                Si tienes problemas con el botón, copia y pega esta URL en tu navegador:
            </div>

            <div class="url-fallback">
                {{ $url }}
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-text">
                <strong>Sistema de Seguridad</strong>
            </div>
            <div class="footer-text">
                Este correo fue enviado automáticamente. Si no solicitaste esta acción, tu cuenta permanece segura.
            </div>
            <div class="footer-text">
                © {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.
            </div>
        </div>
    </div>
</body>

</html>
