
@extends('ecomap.layout')

@section('title', 'Detalles del registro | EcoMap')

@section('content')

<section class="page-banner">
    <div class="container">
        <h1>Detalles del registro de acopio</h1>
        <p>Consulta la información del material recibido en tu centro.</p>
    </div>
</section>

<section class="section">
    <div class="container">

        <div class="form-card">

            <h2>Información del centro</h2>

            <p>
                <strong>Centro de acopio:</strong>
                {{ $registro->centro->nombre ?? 'Centro no disponible' }}
            </p>

            <p>
                <strong>Dirección:</strong>
                {{ $registro->centro->direccion ?? 'Sin información' }}
            </p>

            <p>
                <strong>Municipio:</strong>
                {{ $registro->centro->municipio ?? 'Sin información' }}
            </p>

            <hr>

            <h2>Información del material recibido</h2>

            <p>
                <strong>Material:</strong>
                {{ $registro->material->nombre ?? 'Material no disponible' }}
            </p>

            <p>
                <strong>Cantidad recibida:</strong>
                {{ number_format((float) $registro->cantidad_recibida, 3) }} kg
            </p>

            <p>
                <strong>Fecha de recepción:</strong>
                {{ $registro->fecha->format('d/m/Y') }}
            </p>

            <hr>

            <h2>Información del registro</h2>

            <p>
                <strong>Registrado por:</strong>
                {{ $registro->usuarioRegistra->name ?? 'Usuario no disponible' }}
            </p>

            <p>
                <strong>Fecha de creación:</strong>
                {{ $registro->created_at->format('d/m/Y H:i') }}
            </p>

            <p>
                <strong>Última modificación:</strong>
                {{ $registro->updated_at->format('d/m/Y H:i') }}
            </p>

            <p>
                <strong>Observaciones:</strong>
                {{ $registro->observaciones ?? 'Sin observaciones' }}
            </p>

            <br>

            <a href="{{ route('registros.index') }}" class="btn-secondary">
                Volver al historial
            </a>

        </div>

    </div>
</section>

@endsection
