
@extends('ecomap.layout')

@section('title', 'Detalles de actualización | EcoMap')

@section('content')

<section class="page-banner">
    <div class="container">
        <h1>Detalles de la solicitud</h1>
        <p>Consulta la información y los cambios propuestos para tu centro de acopio.</p>
    </div>
</section>

<section class="section">
    <div class="container">

        <div class="form-card">

            <h2>Información de la solicitud</h2>

            <p>
                <strong>Centro de acopio:</strong>
                {{ $solicitud->centro->nombre ?? 'Centro no disponible' }}
            </p>

            <p>
                <strong>Fecha de solicitud:</strong>
                {{ $solicitud->created_at->format('d/m/Y H:i') }}
            </p>

            <p>
                <strong>Estado:</strong>
                <span class="status">
                    {{ ucfirst($solicitud->estado) }}
                </span>
            </p>

            <p>
                <strong>Fecha de revisión:</strong>
                {{ $solicitud->fecha_revision
                    ? $solicitud->fecha_revision->format('d/m/Y H:i')
                    : 'Pendiente de revisión' }}
            </p>

            <hr>

            <h2>Comparación de información</h2>

            @php
                $datos = $solicitud->datos_propuestos ?? [];

                $campos = [
                    'nombre' => 'Nombre',
                    'descripcion' => 'Descripción',
                    'direccion' => 'Dirección',
                    'colonia' => 'Colonia',
                    'municipio' => 'Municipio',
                    'estado' => 'Estado',
                    'codigo_postal' => 'Código postal',
                    'telefono' => 'Teléfono',
                    'correo' => 'Correo electrónico',
                    'horario' => 'Horario',
                    'latitud' => 'Latitud',
                    'longitud' => 'Longitud',
                ];
            @endphp

            @if(count($datos) && $solicitud->centro)

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Dato</th>
                                <th>Información actual</th>
                                <th>Información propuesta</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($campos as $campo => $etiqueta)
                                <tr>
                                    <th>{{ $etiqueta }}</th>

                                    <td>
                                        {{ $solicitud->centro->{$campo} ?? 'Sin información' }}
                                    </td>

                                    <td>
                                        {{ $datos[$campo] ?? 'Sin información' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            @else

                <p>No hay datos propuestos disponibles para comparar.</p>

            @endif

            <hr>

            <h2>Materiales propuestos</h2>

            @if($solicitud->materiales->isEmpty())

                <p>No se registraron materiales en esta solicitud.</p>

            @else

                <div class="center-tags">
                    @foreach($solicitud->materiales as $material)
                        <span>{{ $material->nombre }}</span>
                    @endforeach
                </div>

            @endif

            <hr>

            <h2>Respuesta administrativa</h2>

            <p>
                {{ $solicitud->observaciones_admin ?? 'Todavía no hay observaciones del administrador.' }}
            </p>

            <br>

            <a href="{{ route('actualizaciones.index') }}" class="btn-secondary">
                Volver al historial
            </a>

        </div>

    </div>
</section>

@endsection