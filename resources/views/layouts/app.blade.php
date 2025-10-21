<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Acceso Seguro')</title>
    @vite('resources/css/app.css')
    @stack('styles')
</head>

<body>
    @yield('content')
</body>

</html>
