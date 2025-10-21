<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #0f172a;
            color: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .wrapper {
            width: min(960px, 90%);
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        h1 {
            font-size: clamp(2rem, 5vw, 3rem);
            margin: 0;
        }

        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
        }

        .card {
            background: linear-gradient(145deg, rgba(30, 64, 175, 0.9), rgba(124, 58, 237, 0.9));
            padding: 1.75rem;
            border-radius: 1.25rem;
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.35);
        }

        .card h2 {
            margin-top: 0;
            font-size: 1.35rem;
            color: #e0e7ff;
        }

        .card p {
            color: #c7d2fe;
            line-height: 1.7;
        }

        form button {
            padding: 0.7rem 1.6rem;
            border-radius: 999px;
            border: none;
            background-color: rgba(248, 250, 252, 0.15);
            color: #f8fafc;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s ease, transform 0.2s ease;
        }

        form button:hover {
            background-color: rgba(248, 250, 252, 0.3);
            transform: translateY(-2px);
        }

        .meta {
            margin-top: 2rem;
            font-size: 0.95rem;
            color: #94a3b8;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <header>
            <div>
                <h1>Panel de Seguridad</h1>
                <p>Hola {{ auth()->user()->name ?? 'usuario' }}, aquí puedes monitorear tus accesos recientes.</p>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit">Cerrar sesión</button>
            </form>
        </header>

        <div class="card-grid">
            <div class="card">
                <h2>Último inicio de sesión</h2>
                <p>{{ optional(auth()->user()->last_login_at)->locale('es')->diffForHumans() ?? 'Sin registros.' }}</p>
            </div>
            <div class="card">
                <h2>Sesión activa</h2>
                <p>{{ auth()->user()->session_token ? __('Sesión validada y segura.') : __('No hay sesión activa registrada.') }}
                </p>
            </div>
            <div class="card">
                <h2>Correo registrado</h2>
                <p>{{ auth()->user()->email }}</p>
            </div>
        </div>

        <p class="meta">Tu sesión se cierra automáticamente si iniciamos otra sesión con las mismas credenciales en un
            dispositivo distinto.</p>
    </div>
</body>

</html>
