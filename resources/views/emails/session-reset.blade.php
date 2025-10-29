<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Eliminación de Sesión Activa</title>
    <style>
        {!! file_get_contents(resource_path('css/email-session-reset.css')) !!}
    </style>
</head>

<body>
    <div class="header">
        <h1>🔐 Solicitud de Eliminación de Sesión</h1>
    </div>

    <div class="content">
        <p>Hola <strong>{{ $user->getName() }}</strong>,</p>

        <p>Hemos detectado que intentaste iniciar sesión desde un nuevo dispositivo, pero ya tienes una sesión activa en
            otro dispositivo.</p>

        <p>Si deseas cerrar tu sesión actual para poder iniciar sesión desde el nuevo dispositivo, haz clic en el
            siguiente botón:</p>

        <div style="text-align: center;">
            <a href="{{ $url }}" class="button">Eliminar Sesión Activa</a>
        </div>

        <div class="warning">
            <strong>⚠️ Importante:</strong>
            <ul>
                <li>Al hacer clic en el botón, se cerrará tu sesión en todos los dispositivos</li>
                <li>Tendrás que volver a iniciar sesión</li>
                <li>Este enlace es válido por 1 hora</li>
                <li>Si no solicitaste esto, puedes ignorar este correo</li>
            </ul>
        </div>

        <p>Si tienes problemas con el botón, copia y pega esta URL en tu navegador:</p>
        <p style="word-break: break-all; color: #007bff;">{{ $url }}</p>
    </div>

    <div class="footer">
        <p>Este correo fue enviado automáticamente desde el sistema de seguridad.</p>
        <p>Si no solicitaste esta acción, tu cuenta permanece segura.</p>
    </div>
</body>

</html>
