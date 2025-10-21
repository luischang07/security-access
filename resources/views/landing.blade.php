<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Seguro</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            color: #0f172a;
            background: linear-gradient(135deg, #1d4ed8, #9333ea);
        }

        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 1.5rem;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.92);
            border-radius: 1.5rem;
            padding: 3rem;
            max-width: 960px;
            width: 100%;
            box-shadow: 0 30px 60px rgba(15, 23, 42, 0.25);
            backdrop-filter: blur(10px);
        }

        h1 {
            font-size: clamp(2.25rem, 5vw, 3.5rem);
            margin-bottom: 1rem;
        }

        p {
            font-size: 1.15rem;
            line-height: 1.8;
            margin-bottom: 2rem;
        }

        .cta {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .btn {
            padding: 0.95rem 2.4rem;
            border-radius: 999px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            font-size: 1rem;
            letter-spacing: 0.02em;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
        }

        .btn-primary {
            background-color: #1d4ed8;
            color: #fff;
            box-shadow: 0 15px 25px rgba(29, 78, 216, 0.35);
        }

        .btn-secondary {
            background-color: #e2e8f0;
            color: #0f172a;
        }

        .btn:hover {
            transform: translateY(-3px);
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
            margin-top: 2.5rem;
        }

        .feature {
            background-color: rgba(226, 232, 240, 0.8);
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: inset 0 0 0 1px rgba(148, 163, 184, 0.2);
        }

        .feature h3 {
            margin-top: 0;
            font-size: 1.2rem;
        }

        .alert {
            margin-bottom: 1.5rem;
            padding: 1rem 1.25rem;
            border-radius: 0.75rem;
            background-color: rgba(16, 185, 129, 0.12);
            color: #047857;
            font-weight: 500;
        }
    </style>
</head>

<body>
    <section class="hero">
        <div class="card">
            @if (session('status'))
                <div class="alert">{{ session('status') }}</div>
            @endif
            <h1>Seguridad de Acceso Centrada en el Usuario</h1>
            <p>
                Protege la información crítica de tu organización con políticas de acceso robustas, seguimiento en
                tiempo real y control total sobre sesiones activas. Nuestra plataforma aplica las mejores prácticas de
                seguridad de Laravel para asegurar credenciales y bloquear accesos no autorizados.
            </p>
            <div class="cta">
                @auth
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-secondary">Cerrar sesión</button>
                    </form>
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">Ir al panel</a>
                @else
                    <a href="{{ route('login.form') }}" class="btn btn-primary">Iniciar sesión</a>
                    <a href="#features" class="btn btn-secondary">Conoce las políticas</a>
                @endauth
            </div>
            <div id="features" class="feature-grid">
                <div class="feature">
                    <h3>Hash Seguro de NIP</h3>
                    <p>Las credenciales se almacenan usando algoritmos de hashing modernos, imposibles de revertir.</p>
                </div>
                <div class="feature">
                    <h3>Validaciones Rigurosas</h3>
                    <p>Patrones personalizados garantizan que los NIP y correos cumplan con los lineamientos
                        corporativos.</p>
                </div>
                <div class="feature">
                    <h3>Sesión Única</h3>
                    <p>Cada usuario mantiene una sola sesión activa, eliminando accesos simultáneos riesgosos.</p>
                </div>
                <div class="feature">
                    <h3>Bloqueos Temporales</h3>
                    <p>Los intentos fallidos se limitan para mitigar ataques de fuerza bruta y proteger la cuenta.</p>
                </div>
            </div>
        </div>
    </section>
</body>

</html>
