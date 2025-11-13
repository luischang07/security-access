<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>{{ __('auth.email_session_reset.title') }}</title>
    <style>
        {!! file_get_contents(resource_path('css/email-session-reset.css')) !!}
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ __('auth.email_session_reset.header') }}</h1>
    </div>

    <div class="content">
        <p>{!! __('auth.email_session_reset.greeting', ['name' => $user->getName()]) !!}</p>

        <p>{{ __('auth.email_session_reset.detected') }}</p>

        <p>{{ __('auth.email_session_reset.instruction') }}</p>

        <div style="text-align: center;">
            <a href="{{ $url }}" class="button">{{ __('auth.email_session_reset.button') }}</a>
        </div>

        <div class="warning">
            <strong>{{ __('auth.email_session_reset.warning_title') }}</strong>
            <ul>
                <li>{!! __('auth.email_session_reset.warning_items.all_devices') !!}</li>
                <li>{{ __('auth.email_session_reset.warning_items.relogin') }}</li>
                <li>{!! __('auth.email_session_reset.warning_items.validity', ['minutes' => 60]) !!}</li>
                <li>{{ __('auth.email_session_reset.warning_items.ignore') }}</li>
            </ul>
        </div>

        <p>{{ __('auth.email_session_reset.url_instruction') }}</p>
        <p style="word-break: break-all; color: #007bff;">{{ $url }}</p>
    </div>

    <div class="footer">
        <p>{{ __('auth.email_session_reset.footer_auto') }}</p>
        <p>{{ __('auth.email_session_reset.footer_secure') }}</p>
    </div>
</body>

</html>
