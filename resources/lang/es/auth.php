<?php

return [
  // Login page
  'login' => [
    'title' => 'Iniciar Sesión',
    'welcome_back' => 'Bienvenido de Nuevo',
    'subtitle' => 'Inicia sesión en tu cuenta para continuar',
    'email' => 'Correo Electrónico',
    'password' => 'Contraseña',
    'remember_me' => 'Recuérdame',
    'submit' => 'Iniciar Sesión',
    'no_account' => '¿No tienes una cuenta?',
    'register_link' => 'Regístrate aquí',
  ],

  // Register page
  'register' => [
    'title' => 'Registro',
    'create_account' => 'Crea tu Cuenta',
    'subtitle' => 'Únete para empezar a gestionar tu salud',
    'name' => 'Nombre Completo',
    'email' => 'Correo Electrónico',
    'password' => 'Contraseña',
    'password_confirmation' => 'Confirmar Contraseña',
    'submit' => 'Crear Cuenta',
    'already_have_account' => '¿Ya tienes una cuenta?',
    'login_link' => 'Inicia sesión aquí',
  ],

  // Session reset success
  'session_reset_success' => [
    'title' => 'Sesión Eliminada',
    'header' => 'Sesión Eliminada Exitosamente',
    'account' => 'Cuenta:',
    'message' => 'Tu sesión activa ha sido eliminada. Ahora puedes iniciar sesión desde este dispositivo.',
    'login_button' => 'Iniciar Sesión',
    'back_to_home' => '← Volver al inicio',
  ],

  // Email - Session Reset
  'email_session_reset' => [
    'title' => 'Eliminación de Sesión Activa',
    'header' => '🔐 Solicitud de Eliminación de Sesión',
    'header_short' => '🔐 Eliminación de Sesión',
    'greeting' => 'Hola <strong>:name</strong>,',
    'greeting_simple' => 'Hola :name,',
    'detected' => 'Hemos detectado que intentaste iniciar sesión desde un nuevo dispositivo, pero ya tienes una sesión activa en otro dispositivo.',
    'detected_strong' => 'Hemos detectado que intentaste iniciar sesión desde un <strong>nuevo dispositivo</strong>, pero ya tienes una sesión activa en otro dispositivo.',
    'instruction' => 'Si deseas cerrar tu sesión actual para poder iniciar sesión desde el nuevo dispositivo, haz clic en el siguiente botón:',
    'button' => 'Eliminar Sesión Activa',
    'button_emoji' => '🗑️ Eliminar Sesión Activa',
    'warning_title' => '⚠️ Importante:',
    'warning_title_info' => '⚠️ Información Importante',
    'warning_items' => [
      'all_devices' => 'Al hacer clic, se cerrará tu sesión en <strong>todos los dispositivos</strong>',
      'relogin' => 'Tendrás que volver a iniciar sesión',
      'validity' => 'Este enlace es válido por <strong>:minutes minutos</strong>',
      'ignore' => 'Si no solicitaste esto, puedes ignorar este correo',
    ],
    'url_instruction' => 'Si tienes problemas con el botón, copia y pega esta URL en tu navegador:',
    'footer_title' => 'Te Acerco Salud',
    'footer_auto' => 'Este correo fue enviado automáticamente desde el sistema Te Acerco Salud.',
    'footer_secure' => 'Si no solicitaste esta acción, tu cuenta permanece segura.',
    'copyright' => '© :year :app_name. Todos los derechos reservados.',
    'dev_info' => '📊 <strong>Información de desarrollo:</strong>',
    'dev_user_id' => 'Usuario ID: :id',
    'dev_timestamp' => 'Timestamp: :timestamp',
    'dev_environment' => 'Entorno: :env',
  ],

  // Common authentication messages
  'failed' => 'Estas credenciales no coinciden con nuestros registros.',
  'password' => 'La contraseña proporcionada es incorrecta.',
  'throttle' => 'Demasiados intentos de inicio de sesión. Por favor intente de nuevo en :seconds segundos.',
];
