
@extends('ecomap.layout')

@section('title', 'Mis solicitudes de actualización | EcoMap')

@section('content')

<section class="page-banner">
    <div class="container">
        <h1>Mis solicitudes de actualización</h1>
        <p>Consulta el historial y el estado de tus solicitudes.</p>
    </div>
</section>

<section class="section">
    <div class="container">

        <div class="form-card">

            @if ($solicitudes->isEmpty())

                <p>Aún no has enviado solicitudes de actualización.</p>

            @else

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Centro de acopio</th>
                                <th>Fecha de solicitud</th>
                                <th>Estado</th>
                                <th>Fecha de revisión</th>
                                <th>Detalles</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($solicitudes as $solicitud)
                                <tr>
                                    <td>
                                        {{ $solicitud->centro->nombre ?? 'Centro no disponible' }}
                                    </td>

                                    <td>
                                        {{ $solicitud->created_at->format('d/m/Y H:i') }}
                                    </td>

                                    <td>
                                        <span class="status">
                                            {{ ucfirst($solicitud->estado) }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $solicitud->fecha_revision
                                            ? $solicitud->fecha_revision->format('d/m/Y H:i')
                                            : 'Pendiente' }}
                                    </td>

                                    <td>
                                        <a href="{{ route('actualizaciones.show', $solicitud->id) }}">
                                            Ver detalles
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            @endif

        </div>

    </div>
</section>

@endsection
