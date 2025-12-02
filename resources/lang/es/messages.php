<?php

return [
  // Login page
  'login' => [
    'title' => 'Iniciar sesión',
    'welcome_back' => 'Bienvenido de nuevo',
    'description' => 'Inicia sesión para acceder al panel seguro.',
    'email_label' => 'Correo electrónico',
    'nip_label' => 'NIP',
    'remember_me' => 'Recordarme',
    'submit' => 'Ingresar',
    'back_to_home' => '← Volver al inicio',
    'session_active' => [
      'title' => '🔐 Sesión Activa Detectada',
      'description' => 'Si quieres eliminar tu sesión activa para poder iniciar sesión desde este dispositivo, haz clic en el botón de abajo:',
      'button' => '📧 Enviar email para eliminar sesión',
      'email_info' => 'Se enviará un correo a <strong>:email</strong> con un enlace para eliminar tu sesión activa.',
    ],
    'lockout' => 'Cuenta bloqueada temporalmente. Intenta nuevamente en :time.',
  ],

  // Register page
  'register' => [
    'title' => 'Registro de usuario',
    'create_account' => 'Crear cuenta',
    'description' => 'Complete el formulario para registrarse en el sistema.',
    'name_label' => 'Nombre completo',
    'email_label' => 'Correo electrónico',
    'nip_label' => 'NIP',
    'nip_requirements' => 'Debe contener al menos 8 caracteres, incluyendo una mayúscula, un número y un símbolo.',
    'nip_confirmation_label' => 'Confirmar NIP',
    'submit' => 'Registrarse',
    'back_to_home' => '← Volver al inicio',
    'already_have_account' => '¿Ya tienes cuenta? Inicia sesión',
  ],

  // Landing page
  'landing' => [
    'title' => 'Acceso Seguro',
    'hero_title' => 'Seguridad de Acceso',
    'get_started' => 'Comience ahora',
    'register' => 'Registrarse',
    'logout' => 'Cerrar sesión',
    'go_to_dashboard' => 'Ir al panel',
  ],

  // Dashboard
  'dashboard' => [
    'title' => 'Panel de Control',
    'welcome' => 'Bienvenido, <strong>:name</strong>. Aquí puedes monitorear la seguridad de tu cuenta.',
    'logout' => 'Cerrar sesión',
    'last_login' => [
      'title' => 'Último inicio de sesión',
      'no_records' => 'Sin registros.',
    ],
    'active_session' => [
      'title' => 'Sesión activa',
      'validated' => 'Sesión validada y segura.',
      'no_session' => 'No hay sesión activa registrada.',
    ],
    'registered_email' => [
      'title' => 'Correo registrado',
    ],
    'info' => 'Tu sesión se cierra automáticamente si iniciamos otra sesión con las mismas credenciales en un dispositivo distinto.',
  ],

  // Navbar
  'navbar' => [
    'brand' => 'Te Acerco Salud',
    'home' => 'Inicio',
    'features' => 'Características',
    'about' => 'Acerca de',
    'services' => 'Servicios',
    'contact' => 'Contacto',
    'dashboard' => 'Panel',
    'login' => 'Iniciar sesión',
  ],

  // Footer
  'footer' => [
    'title' => 'Te Acerco Salud',
    'description' => 'Seguridad de acceso centrada en el usuario',
    'home' => 'Inicio',
    'features' => 'Características',
    'about' => 'Acerca de',
    'services' => 'Servicios',
    'contact' => 'Contacto',
    'copyright' => '© :year Te Acerco Salud. Todos los derechos reservados.',
  ],

  // Session Reset Success
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

  // Common
  'status' => 'Estado',
];
