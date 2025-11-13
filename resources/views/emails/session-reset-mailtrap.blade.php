<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('auth.email_session_reset.title') }}</title>
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
            <h1>{{ __('auth.email_session_reset.header_short') }}</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                {{ __('auth.email_session_reset.greeting_simple', ['name' => $user->getName()]) }}
            </div>

            <div class="message">
                {!! __('auth.email_session_reset.detected_strong') !!}
            </div>

            <div class="message">
                {{ __('auth.email_session_reset.instruction') }}
            </div>

            <!-- Call to Action -->
            <div class="cta-container">
                <a href="{{ $url }}" class="cta-button">
                    {{ __('auth.email_session_reset.button_emoji') }}
                </a>
            </div>

            <!-- Warning Box -->
            <div class="warning-box">
                <div class="warning-title">
                    {{ __('auth.email_session_reset.warning_title_info') }}
                </div>
                <ul class="warning-list">
                    <li>{!! __('auth.email_session_reset.warning_items.all_devices') !!}</li>
                    <li>{{ __('auth.email_session_reset.warning_items.relogin') }}</li>
                    <li>{!! __('auth.email_session_reset.warning_items.validity', ['minutes' => $expiresIn]) !!}</li>
                    <li>{{ __('auth.email_session_reset.warning_items.ignore') }}</li>
                </ul>
            </div>

            <!-- Stats (only visible in development) -->
            @if (config('app.debug'))
                <div class="stats">
                    {!! __('auth.email_session_reset.dev_info') !!}<br>
                    {{ __('auth.email_session_reset.dev_user_id', ['id' => $user->getId()]) }}<br>
                    {{ __('auth.email_session_reset.dev_timestamp', ['timestamp' => now()->format('Y-m-d H:i:s')]) }}<br>
                    {{ __('auth.email_session_reset.dev_environment', ['env' => config('app.env')]) }}
                </div>
            @endif

            <div class="message">
                {{ __('auth.email_session_reset.url_instruction') }}
            </div>

            <div class="url-fallback">
                {{ $url }}
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-text">
                <strong>{{ __('auth.email_session_reset.footer_title') }}</strong>
            </div>
            <div class="footer-text">
                {{ __('auth.email_session_reset.footer_auto') }} {{ __('auth.email_session_reset.footer_secure') }}
            </div>
            <div class="footer-text">
                {{ __('auth.email_session_reset.copyright', ['year' => date('Y'), 'app_name' => config('app.name')]) }}
            </div>
        </div>
    </div>
</body>

</html>
