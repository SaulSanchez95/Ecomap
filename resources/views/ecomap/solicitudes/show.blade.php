
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detalles de la solicitud de acopio</h1>

    <div class="card">
        <div class="card-body">

            <h3>Información del centro</h3>

            <p>
                <strong>Centro:</strong>
                {{ $solicitud->centro->nombre }}
            </p>

            <p>
                <strong>Dirección:</strong>
                {{ $solicitud->centro->direccion }},
                {{ $solicitud->centro->municipio }}
            </p>

            <hr>

            <h3>Información del ciudadano</h3>

            <p>
                <strong>Nombre:</strong>
                {{ $solicitud->ciudadano->name }}
            </p>

            <p>
                <strong>Correo:</strong>
                {{ $solicitud->ciudadano->email }}
            </p>

            <p>
                <strong>Teléfono:</strong>
                {{ $solicitud->ciudadano->telefono ?? 'No proporcionado' }}
            </p>

            <hr>

            <h3>Datos de la entrega</h3>

            <p>
                <strong>Fecha estimada:</strong>
                {{ $solicitud->fecha_entrega_estimada?->format('d/m/Y') ?? 'No especificada' }}
            </p>

            <p>
                <strong>Estado:</strong>
                {{ ucfirst($solicitud->estado) }}
            </p>

            <p>
                <strong>Comentarios:</strong>
                {{ $solicitud->comentarios ?? 'Sin comentarios' }}
            </p>

            <hr>

            <h3>Materiales que llevará el ciudadano</h3>

            @if ($solicitud->materiales->isEmpty())
                <p>No se registraron materiales.</p>
            @else
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Material</th>
                                <th>Cantidad estimada</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($solicitud->materiales as $material)
                                <tr>
                                    <td>{{ $material->nombre }}</td>
                                    <td>
                                        {{ $material->pivot->cantidad_estimada }} kg
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <a href="{{ route('solicitudes.index') }}" class="btn btn-secondary">
                Volver a solicitudes
            </a>

        </div>
    </div>
</div>
@endsection
