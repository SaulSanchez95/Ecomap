
@extends('ecomap.layout')

@section('title', 'Información del centro | EcoMap')

@section('content')

<section class="page-banner">
    <div class="container">
        <span class="tag">CENTRO DE ACOPIO</span>

        <h1>{{ $centro->nombre }}</h1>

        <p>
            Información del centro y materiales que acepta.
        </p>
    </div>
</section>

<section class="section">
    <div class="container">

        <div class="form-card">

            <h2>Información general</h2>

            <p>
                <strong>Descripción:</strong>
                {{ $centro->descripcion ?? 'Sin descripción disponible.' }}
            </p>

            <p>
                <strong>Dirección:</strong>
                {{ $centro->direccion }}
            </p>

            <p>
                <strong>Colonia:</strong>
                {{ $centro->colonia ?? 'No especificada' }}
            </p>

            <p>
                <strong>Municipio:</strong>
                {{ $centro->municipio }}
            </p>

            <p>
                <strong>Estado:</strong>
                {{ $centro->estado }}
            </p>

            <p>
                <strong>Código postal:</strong>
                {{ $centro->codigo_postal ?? 'No especificado' }}
            </p>

            <p>
                <strong>Teléfono:</strong>
                {{ $centro->telefono ?? 'No disponible' }}
            </p>

            <p>
                <strong>Correo:</strong>
                {{ $centro->correo ?? 'No disponible' }}
            </p>

            <p>
                <strong>Horario de atención:</strong>
                {{ $centro->horario ?? 'No especificado' }}
            </p>

            <h3>Materiales aceptados</h3>

            <div class="center-tags">

                @forelse($centro->materiales as $material)
                    <span>{{ $material->nombre }}</span>
                @empty
                    <span>Sin materiales registrados</span>
                @endforelse

            </div>

            <br>

            <a
                href="{{ route('solicitudes.create') }}"
                class="btn-primary"
            >
                Registrar preinscripción
            </a>

            <a
                href="{{ route('ciudadano') }}"
                class="btn-secondary"
            >
                Volver al catálogo
            </a>

        </div>

    </div>
</section>

@endsection
