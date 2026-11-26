@extends('ecomap.layout')

@section('title', 'EcoMap | Panel del encargado')

@section('content')

<section class="page-banner">
    <div class="container">
        <span class="tag">PANEL DEL ENCARGADO</span>

        <h1>Panel del encargado</h1>

        <p>
            Administra tus centros de acopio, registra las recepciones
            de materiales y gestiona tus campañas de reciclaje.
        </p>
    </div>
</section>

<section class="section">
    <div class="container">

        @if(session('success'))
            <div class="status">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="status">
                {{ session('error') }}
            </div>
        @endif

        <div class="dashboard-header">
            <div>
                <h2>Gestión de EcoMap</h2>
                <p>
                    Selecciona la operación que deseas realizar.
                </p>
            </div>
        </div>

        <div class="dashboard-grid">

            {{-- Registrar recepción de materiales --}}
            <div class="form-card">
                <h2>Registrar recepción</h2>

                <p>
                    Registra las cantidades reales de materiales
                    recibidos en tus centros de acopio.
                </p>

                <a
                    href="{{ route('registros.create') }}"
                    class="btn-primary"
                >
                    Registrar recepción
                </a>

                <br><br>

                <a
                    href="{{ route('registros.index') }}"
                    class="btn-secondary"
                >
                    Consultar historial
                </a>
            </div>

            {{-- Administrar información de los centros --}}
            <div class="form-card">
                <h2>Información de mis centros</h2>

                <p>
                    Consulta tus centros registrados y solicita
                    actualizaciones de sus datos.
                </p>

                <a
                    href="{{ route('centros.index') }}"
                    class="btn-primary"
                >
                    Actualizar información
                </a>
            </div>

            {{-- Crear campañas --}}
            <div class="form-card">
                <h2>Campañas de reciclaje</h2>

                <p>
                    Registra campañas de recolección, selecciona
                    los materiales necesarios e indica las
                    cantidades solicitadas.
                </p>

                <p class="section-description">
                    Las nuevas campañas se guardan inicialmente
                    como borradores.
                </p>

                <a
                    href="{{ route('campanias.create') }}"
                    class="btn-primary"
                >
                    Crear campaña
                </a>

                <br><br>

                <a
                    href="{{ route('campanias.index') }}"
                    class="btn-secondary"
                >
                    Consultar campañas
                </a>
            </div>

        </div>

    </div>
</section>

@endsection
