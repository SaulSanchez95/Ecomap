
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'EcoMap')</title>

    <link rel="stylesheet" href="{{ asset('css/ecomap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/usuarios.css') }}">
</head>

<body>

    <header class="navbar">

        {{-- Logotipo --}}
        <a href="{{ route('inicio') }}" class="logo">
            <span class="logo-icon">♻</span>
            <span>Eco<span>Map</span></span>
        </a>

        {{-- Menú de navegación --}}
        <nav class="menu">

            <a href="{{ route('inicio') }}">
                Inicio
            </a>

            @auth

                @if(auth()->user()->esCiudadano())

                    <a href="{{ route('ciudadano') }}">
                        Centros de acopio
                    </a>

                @elseif(auth()->user()->esDueno())

                    <a href="{{ route('encargado') }}">
                        Panel del encargado
                    </a>

                    <a href="{{ route('centros.index') }}">
                        Mis centros
                    </a>

                @elseif(auth()->user()->esAdministrador())

                    <a href="{{ route('admin.dashboard') }}">
                        Administración
                    </a>

                @endif

            @endauth

        </nav>

        {{-- Acciones de usuario --}}
        @auth

            <div class="usuario-acciones">

                <a
                    href="{{ route('perfil') }}"
                    class="btn-secondary"
                >
                    Mi perfil
                </a>

                <form
                    action="{{ route('logout') }}"
                    method="POST"
                >
                    @csrf

                    <button
                        type="submit"
                        class="btn-primary"
                    >
                        Cerrar sesión
                    </button>

                </form>

            </div>

        @else

            <div class="usuario-acciones">

                <a
                    href="{{ route('login') }}"
                    class="btn-secondary"
                >
                    Iniciar sesión
                </a>

                <a
                    href="{{ route('register') }}"
                    class="btn-primary"
                >
                    Crear cuenta
                </a>

            </div>

        @endauth

    </header>

    <main>
        @yield('content')
    </main>

    <footer class="footer">

        <h2>EcoMap ♻</h2>

        <p>
            Conectando personas con centros de reciclaje en Colima.
        </p>

        <p class="copyright">
            Proyecto EcoMap | 2026
        </p>

    </footer>

</body>

</html>
