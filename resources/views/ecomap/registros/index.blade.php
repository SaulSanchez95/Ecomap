@extends('ecomap.layout')

@section('title', 'Registros diarios de acopio | EcoMap')

@section('content')

<section class="page-banner">
    <div class="container">
        <h1>Registros diarios de acopio</h1>
        <p>Consulta las cantidades reales de materiales recibidos en tus centros.</p>
    </div>
</section>

<section class="section">
    <div class="container">

        <div class="form-card">

            @if (session('success'))
                <div class="status">
                    {{ session('success') }}
                </div>
            @endif

            <br>

            <a href="{{ route('registros.create') }}" class="btn-primary">
                Registrar recepción
            </a>

            <br>
            <br>

            @if ($registros->isEmpty())

                <p>Aún no hay registros de material recibido.</p>

            @else

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Centro de acopio</th>
                                <th>Material</th>
                                <th>Cantidad recibida</th>
                                <th>Registrado por</th>
                                <th>Detalles</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($registros as $registro)
                                <tr>
                                    <td>
                                        {{ $registro->fecha->format('d/m/Y') }}
                                    </td>

                                    <td>
                                        {{ $registro->centro->nombre ?? 'Centro no disponible' }}
                                    </td>

                                    <td>
                                        {{ $registro->material->nombre ?? 'Material no disponible' }}
                                    </td>

                                    <td>
                                        {{ number_format((float) $registro->cantidad_recibida, 3) }} kg
                                    </td>

                                    <td>
                                        {{ $registro->usuarioRegistra->name ?? 'Usuario no disponible' }}
                                    </td>

                                    <td>
                                        <a href="{{ route('registros.show', $registro->id) }}">
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
