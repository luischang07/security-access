<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ __('emails.order_ready.title') }}</title>
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
      <h1>{{ __('emails.order_ready.header') }}</h1>
    </div>

    <!-- Content -->
    <div class="content">
      <div class="greeting">
        {{ __('emails.order_ready.greeting', ['name' => $user->getNombre() ?? $user->getApellido()]) }}
      </div>

      <div class="message">
        {!! __('emails.order_ready.body_ready', ['folio' => $pedido->getFolio()]) !!}
      </div>

      <div class="message">
        {!! __('emails.order_ready.body_time') !!}
        <br>
        <strong>{{ $pedido->getSucursal()->getNombre() }}</strong>
      </div>

      <!-- Call to Action -->
      <div class="cta-container">
        <a href="{{ route('patient.orders.show', $pedido->getFolio()) }}" class="cta-button">
          {{ __('emails.order_ready.cta_button') }}
        </a>
      </div>

      <!-- Warning Box -->
      <div class="warning-box">
        <div class="warning-title">
          {{ __('emails.order_ready.warning_title') }}
        </div>
        <ul class="warning-list">
          <li>{{ __('emails.order_ready.warning_id') }}</li>
          <li>{{ __('emails.order_ready.warning_cancel') }}</li>
          <li>{{ __('emails.order_ready.warning_penalty') }}</li>
        </ul>
      </div>

      <div class="message">
        {{ __('emails.order_ready.support') }}
      </div>
    </div>

    <!-- Footer -->
    <div class="footer">
      <div class="footer-text">
        <strong>{{ __('emails.order_ready.footer_app') }}</strong>
      </div>
      <div class="footer-text">
        {{ __('auth.email_session_reset.copyright', ['year' => date('Y'), 'app_name' => config('app.name')]) }}
      </div>
    </div>
  </div>
</body>

</html>