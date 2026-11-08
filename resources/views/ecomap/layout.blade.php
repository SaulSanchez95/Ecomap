<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'EcoMap')</title>
    <link rel="stylesheet" href="{{ asset('css/ecomap.css') }}">
</head>
<body>
    <header class="navbar">
        <a href="{{ route('inicio') }}" class="logo">
            <span class="logo-icon">♻</span>
            <span>Eco<span>Map</span></span>
        </a>
        <nav class="menu">
            <a href="{{ route('inicio') }}">Inicio</a>
            <a href="{{ route('ciudadano') }}">Ciudadanos</a>
            <a href="{{ route('encargado') }}">Centros de acopio</a>
        </nav>
        <a href="{{ route('ciudadano') }}" class="btn-primary">
            Explorar centros
        </a>
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
/*Pepe Pollo