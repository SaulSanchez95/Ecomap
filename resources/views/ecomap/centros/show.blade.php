
@extends('ecomap.layout')

@section('title', 'Detalles del centro | EcoMap')

@section('content')

<section class="page-banner">
    <div class="container">
        <h1>{{ $centro->nombre }}</h1>
        <p>Información general del centro de acopio.</p>
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

        <div class="form-card">

            <h2>Información del centro</h2>

            <p><strong>Descripción:</strong>
                {{ $centro->descripcion ?? 'Sin descripción registrada' }}
            </p>

            <p><strong>Dirección:</strong>
                {{ $centro->direccion }}
            </p>

            <p><strong>Colonia:</strong>
                {{ $centro->colonia ?? 'No registrada' }}
            </p>

            <p><strong>Municipio:</strong>
                {{ $centro->municipio }}
            </p>

            <p><strong>Estado:</strong>
                {{ $centro->estado }}
            </p>

            <p><strong>Código postal:</strong>
                {{ $centro->codigo_postal ?? 'No registrado' }}
            </p>

            <p><strong>Teléfono:</strong>
                {{ $centro->telefono ?? 'No registrado' }}
            </p>

            <p><strong>Correo:</strong>
                {{ $centro->correo ?? 'No registrado' }}
            </p>

            <p><strong>Horario:</strong>
                {{ $centro->horario ?? 'No registrado' }}
            </p>

            <p><strong>Estado de validación:</strong>
                <span class="status">
                    {{ ucfirst($centro->estado_validacion) }}
                </span>
            </p>

            <p><strong>Estado del centro:</strong>
                {{ $centro->activo ? 'Activo' : 'Inactivo' }}
            </p>

            <h3>Materiales aceptados</h3>

            <div class="center-tags">
                @forelse($centro->materiales as $material)
                    <span>{{ $material->nombre }}</span>
                @empty
                    <span>Sin materiales registrados</span>
                @endforelse
            </div>

         @if($centro->estado_validacion === 'validado' && $centro->activo)

    @if($tieneSolicitudPendiente)
        <div class="status">
            Este centro ya tiene una solicitud de actualización pendiente.
            Debes esperar a que el administrador la revise antes de enviar otra.
        </div>
    @else
        <br>

        <a href="{{ route('centros.edit', $centro->id) }}"
           class="btn-primary">
            Solicitar actualización
        </a>
    @endif

@endif

            <a href="{{ route('centros.index') }}"
               class="btn-secondary">
                Volver a mis centros
            </a>

        </div>

    </div>
</section>

@endsection
