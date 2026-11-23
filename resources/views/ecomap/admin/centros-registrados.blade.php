@extends('ecomap.layout')

@section('title', 'Centros registrados - EcoMap')

@section('content')

<style>
    .centros-admin-panel {
        max-width: 1250px;
        margin: 40px auto;
        padding: 20px;
    }

    .centros-admin-panel h1 {
        color: #237a43;
        margin-bottom: 10px;
    }

    .centros-admin-descripcion {
        color: #666;
        margin-bottom: 25px;
    }

    .centros-admin-volver {
        display: inline-block;
        margin-bottom: 20px;
        color: #237a43;
        text-decoration: none;
        font-weight: bold;
    }

    .centros-admin-tabla-contenedor {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 12px #00000015;
        overflow-x: auto;
    }

    .centros-admin-tabla {
        width: 100%;
        border-collapse: collapse;
    }

    .centros-admin-tabla th {
        background: #237a43;
        color: white;
        padding: 14px;
        text-align: left;
    }

    .centros-admin-tabla td {
        padding: 14px;
        border-bottom: 1px solid #ddd;
        vertical-align: top;
    }

    .centros-admin-tabla tr:hover {
        background: #f1f8f2;
    }

    .centros-admin-estado {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: bold;
    }

    .centros-admin-pendiente {
        background: #fef3c7;
        color: #92400e;
    }

    .centros-admin-validado {
        background: #dcfce7;
        color: #166534;
    }

    .centros-admin-rechazado {
        background: #fee2e2;
        color: #991b1b;
    }

    .centros-admin-detalles summary {
        color: #237a43;
        font-weight: bold;
        cursor: pointer;
    }

    .centros-admin-detalles p {
        margin: 8px 0;
        overflow-wrap: anywhere;
    }

    .centros-admin-paginacion {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        margin-top: 20px;
    }

    .centros-admin-paginacion a {
        color: #237a43;
        font-weight: bold;
    }
</style>

<div class="centros-admin-panel">

    <a href="{{ route('admin.dashboard') }}"
       class="centros-admin-volver">
        ← Volver a Administración
    </a>

    <h1>Centros de acopio registrados</h1>

    <p class="centros-admin-descripcion">
        Consulta los centros de acopio registrados
        en EcoMap y su estado de validación.
    </p>

    <div class="centros-admin-tabla-contenedor">

@if(session('success'))
    <p style="color:green;">{{ session('success') }}</p>
@endif

@if(session('error'))
    <p style="color:red;">{{ session('error') }}</p>
@endif

@if($errors->any())
    <p style="color:red;">
        Selecciona al menos un centro válido.
    </p>
@endif

<form method="POST"
      action="{{ route('admin.centros.baja') }}"
      onsubmit="return confirm('¿Seguro que deseas dar de baja los centros seleccionados?');">
    @csrf

        <table class="centros-admin-tabla">

            <thead>
                <tr>

<th>Seleccionar</th>

                    <th>ID</th>
                    <th>Centro</th>
                    <th>Propietario</th>
                    <th>Municipio</th>
                    <th>Estado de validación</th>
                    <th>Información</th>
                </tr>
            </thead>

            <tbody>

                @forelse($centros as $centro)

                    <tr>

<td>
    <input type="checkbox"
           name="centros[]"
           value="{{ $centro->id }}">
</td>

                        <td>{{ $centro->id }}</td>

                        <td>
                            <strong>{{ $centro->nombre }}</strong>
                        </td>

                        <td>
                            {{ $centro->dueno?->name ?? 'No disponible' }}
                        </td>

                        <td>{{ $centro->municipio }}</td>

                        <td>
                            @if($centro->estado_validacion === 'validado')
                                <span class="centros-admin-estado centros-admin-validado">
                                    Validado
                                </span>
                            @elseif($centro->estado_validacion === 'rechazado')
                                <span class="centros-admin-estado centros-admin-rechazado">
                                    Rechazado
                                </span>
                            @elseif($centro->estado_validacion === 'pendiente')
                                <span class="centros-admin-estado centros-admin-pendiente">
                                    Pendiente
                                </span>
                            @else
                                {{ ucfirst($centro->estado_validacion) }}
                            @endif
                        </td>

                        <td>
                            <details class="centros-admin-detalles">
                                <summary>Ver detalles</summary>

                                <p>
                                    <strong>Descripción:</strong>
                                    {{ $centro->descripcion ?? 'No registrada' }}
                                </p>

                                <p>
                                    <strong>Dirección:</strong>
                                    {{ $centro->direccion }}
                                </p>

                                <p>
                                    <strong>Colonia:</strong>
                                    {{ $centro->colonia ?? 'No registrada' }}
                                </p>

                                <p>
                                    <strong>Estado:</strong>
                                    {{ $centro->estado }}
                                </p>

                                <p>
                                    <strong>Código postal:</strong>
                                    {{ $centro->codigo_postal ?? 'No registrado' }}
                                </p>

                                <p>
                                    <strong>Teléfono:</strong>
                                    {{ $centro->telefono ?? 'No registrado' }}
                                </p>

                                <p>
                                    <strong>Correo:</strong>
                                    {{ $centro->correo ?? 'No registrado' }}
                                </p>

                                <p>
                                    <strong>Horario:</strong>
                                    {{ $centro->horario ?? 'No registrado' }}
                                </p>

                                <p>
                                    <strong>Coordenadas:</strong>
                                    {{ $centro->latitud ?? 'N/D' }},
                                    {{ $centro->longitud ?? 'N/D' }}
                                </p>

                                <p>
                                    <strong>Estado operativo:</strong>
                                    {{ $centro->activo ? 'Activo' : 'Inactivo' }}
                                </p>

                                <p>
                                    <strong>Materiales aceptados:</strong>
                                    {{ $centro->materiales->pluck('nombre')->join(', ') ?: 'Ninguno registrado' }}
                                </p>

                                <p>
                                    <strong>Fecha de registro:</strong>
                                    {{ $centro->created_at?->format('d/m/Y H:i') ?? 'No disponible' }}
                                </p>

                                <p>
                                    <strong>Observaciones administrativas:</strong>
                                    {{ $centro->observaciones_admin ?? 'Sin observaciones' }}
                                </p>

                            </details>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="7" style="text-align:center;">
                            No hay centros de acopio registrados.
                        </td>
                    </tr>
                @endforelse

            </tbody>

        </table>

<div style="margin-top:20px;">
    <button type="submit"
            @disabled($centros->isEmpty())
            style="background:#c62828;
                   color:white;
                   border:none;
                   padding:12px 22px;
                   border-radius:8px;
                   font-weight:bold;
                   cursor:pointer;">
        Dar de baja seleccionados
    </button>
</div>

</form>

        @if($centros->hasPages())
            <div class="centros-admin-paginacion">

                @if($centros->onFirstPage())
                    <span>Anterior</span>
                @else
                    <a href="{{ $centros->previousPageUrl() }}">
                        Anterior
                    </a>
                @endif

                <span>
                    Página {{ $centros->currentPage() }}
                    de {{ $centros->lastPage() }}
                </span>

                @if($centros->hasMorePages())
                    <a href="{{ $centros->nextPageUrl() }}">
                        Siguiente
                    </a>
                @else
                    <span>Siguiente</span>
                @endif

            </div>
        @endif

    </div>

</div>

@endsection
