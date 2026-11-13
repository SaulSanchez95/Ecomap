@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Solicitudes de acopio recibidas</h1>

    @if ($solicitudes->isEmpty())
        <p>Aún no has recibido solicitudes de acopio.</p>
    @else
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Centro de acopio</th>
                        <th>Ciudadano</th>
                        <th>Fecha estimada</th>
                        <th>Materiales</th>
                        <th>Estado</th>
                        <th>Detalles</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($solicitudes as $solicitud)
                        <tr>
                            <td>{{ $solicitud->centro->nombre }}</td>
                            <td>{{ $solicitud->ciudadano->name }}</td>
                            <td>
                                {{ $solicitud->fecha_entrega_estimada->format('d/m/Y') }}
                            </td>
                            <td>
                                @foreach ($solicitud->materiales as $material)
                                    <div>
                                        {{ $material->nombre }}:
                                        {{ $material->pivot->cantidad_estimada }} kg
                                    </div>
                                @endforeach
                            </td>
                            <td>{{ ucfirst($solicitud->estado) }}</td>
                            <td>
                                <a href="{{ route('solicitudes.show', $solicitud->id) }}">
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
@endsection