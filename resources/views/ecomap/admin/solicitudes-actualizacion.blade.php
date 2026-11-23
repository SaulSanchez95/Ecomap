@extends('ecomap.layout')

@section('title', 'Solicitudes de actualización - EcoMap')

@section('content')

<style>
    .actualizaciones-admin {
        max-width: 1200px;
        margin: 40px auto;
        padding: 20px;
    }

    .actualizaciones-admin h1 {
        color: #237a43;
        margin-bottom: 12px;
    }

    .actualizaciones-descripcion {
        color: #666;
        margin-bottom: 25px;
    }

    .actualizaciones-volver {
        display: inline-block;
        margin-bottom: 20px;
        color: #237a43;
        text-decoration: none;
        font-weight: bold;
    }

    .actualizacion-tarjeta {
        background: white;
        padding: 25px;
        margin-bottom: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 12px #00000015;
    }

    .actualizacion-tarjeta h2 {
        color: #237a43;
        margin-bottom: 15px;
    }

    .actualizacion-tarjeta p {
        margin-bottom: 10px;
    }

    .actualizacion-tabla-contenedor {
        overflow-x: auto;
        margin-top: 20px;
    }

    .actualizacion-tabla {
        width: 100%;
        border-collapse: collapse;
    }

    .actualizacion-tabla th {
        background: #237a43;
        color: white;
        text-align: left;
        padding: 14px;
    }

    .actualizacion-tabla td {
        padding: 14px;
        border-bottom: 1px solid #ddd;
        overflow-wrap: anywhere;
    }

    .actualizacion-tabla tr:nth-child(even) {
        background: #f2f8f3;
    }

    .dato-anterior {
        color: #666;
    }

    .dato-nuevo {
        color: #237a43;
        font-weight: bold;
    }

    .actualizacion-estado {
        display: inline-block;
        background: #fef3c7;
        color: #92400e;
        border-radius: 20px;
        padding: 6px 12px;
        font-weight: bold;
    }

    .actualizacion-aviso {
        background: #fff7e6;
        padding: 15px;
        margin-top: 20px;
        border-radius: 8px;
        color: #805800;
    }
</style>

<div class="actualizaciones-admin">

    <a href="{{ route('admin.dashboard') }}"
       class="actualizaciones-volver">
        ← Volver a Administración
    </a>

    <h1>Solicitudes de actualización de centros</h1>

    <p class="actualizaciones-descripcion">
        Revisa los datos actuales y las modificaciones
        solicitadas por los propietarios de los centros.
    </p>

    @forelse($solicitudes as $solicitud)

        @php
            $centro = $solicitud->centro;
            $propuestos = $solicitud->datos_propuestos ?? [];

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
                'longitud' => 'Longitud'
            ];

            $cambios = [];

            foreach ($campos as $campo => $etiqueta) {

                if (!array_key_exists($campo, $propuestos)) {
                    continue;
                }

                $anterior = data_get($centro, $campo);
                $nuevo = $propuestos[$campo];

                if ((string) ($anterior ?? '') !== (string) ($nuevo ?? '')) {
                    $cambios[] = [
                        'campo' => $etiqueta,
                        'anterior' => $anterior,
                        'nuevo' => $nuevo
                    ];
                }
            }

            $materialesActuales = $centro
                ? $centro->materiales->pluck('nombre')->sort()->values()->all()
                : [];

            $materialesNuevos = $solicitud->materiales
                ->pluck('nombre')->sort()->values()->all();
        @endphp

        <div class="actualizacion-tarjeta">

            <h2>
                {{ $centro?->nombre ?? 'Centro no disponible' }}
            </h2>

            <p>
                <strong>Número de solicitud:</strong>
                #{{ $solicitud->id }}
            </p>

            <p>
                <strong>Propietario:</strong>
                {{ $solicitud->solicitante?->name ?? 'No disponible' }}
            </p>

            <p>
                <strong>Fecha de solicitud:</strong>
                {{ $solicitud->created_at?->format('d/m/Y H:i') }}
            </p>

            <p>
                <strong>Estado:</strong>
                <span class="actualizacion-estado">
                    Pendiente
                </span>
            </p>

            <h3 style="margin-top:25px;">
                Comparación de datos
            </h3>

            <div class="actualizacion-tabla-contenedor">

                <table class="actualizacion-tabla">

                    <thead>
                        <tr>
                            <th>Campo</th>
                            <th>Dato actual</th>
                            <th>Dato solicitado</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($cambios as $cambio)
                            <tr>
                                <td>
                                    <strong>{{ $cambio['campo'] }}</strong>
                                </td>

                                <td class="dato-anterior">
                                    {{ $cambio['anterior'] ?? 'Sin dato' }}
                                </td>

                                <td class="dato-nuevo">
                                    {{ $cambio['nuevo'] ?? 'Sin dato' }}
                                </td>
                            </tr>
                        @endforeach

                        @if($materialesActuales !== $materialesNuevos)
                            <tr>
                                <td>
                                    <strong>Materiales aceptados</strong>
                                </td>

                                <td class="dato-anterior">
                                    {{ implode(', ', $materialesActuales) ?: 'Sin materiales' }}
                                </td>

                                <td class="dato-nuevo">
                                    {{ implode(', ', $materialesNuevos) ?: 'Sin materiales' }}
                                </td>
                            </tr>
                        @endif

                        @if(empty($cambios) && $materialesActuales === $materialesNuevos)
                            <tr>
                                <td colspan="3">
                                    No se detectaron cambios en esta solicitud.
                                </td>
                            </tr>
                        @endif

                    </tbody>

                </table>

            </div>

            <div class="actualizacion-aviso">
                Los cambios todavía no se han aplicado.
                Esta solicitud requiere revisión administrativa.
            </div>

<!-- Acciones administrativas -->
<div style="margin-top:25px; display:flex;
            flex-wrap:wrap; gap:20px;">

    <!-- Aprobar actualización -->
    <form method="POST"
          action="{{ route('admin.actualizaciones.aprobar', $solicitud->id) }}"
          onsubmit="return confirm('¿Seguro que deseas aprobar estos cambios?');">

        @csrf

        <button type="submit"
                style="background:#237a43;
                       color:white;
                       border:none;
                       border-radius:8px;
                       padding:12px 24px;
                       font-weight:bold;
                       cursor:pointer;">
            ✓ Aprobar actualización
        </button>
    </form>

    <!-- Rechazar actualización -->
    <form method="POST"
          action="{{ route('admin.actualizaciones.rechazar', $solicitud->id) }}"
          onsubmit="return confirm('¿Seguro que deseas rechazar esta solicitud?');">

        @csrf

        <label for="motivo-{{ $solicitud->id }}"
               style="display:block; font-weight:bold;
                      margin-bottom:8px;">
            Motivo del rechazo:
        </label>

        <textarea
            id="motivo-{{ $solicitud->id }}"
            name="observaciones_admin"
            required
            maxlength="1000"
            rows="3"
            style="display:block;
                   width:100%;
                   min-width:260px;
                   box-sizing:border-box;
                   padding:10px;
                   border:1px solid #ccc;
                   border-radius:8px;
                   margin-bottom:10px;"
            placeholder="Explica por qué se rechaza la solicitud"></textarea>

        <button type="submit"
                style="background:#c62828;
                       color:white;
                       border:none;
                       border-radius:8px;
                       padding:12px 24px;
                       font-weight:bold;
                       cursor:pointer;">
            ✕ Rechazar actualización
        </button>
    </form>

</div>

        </div>

    @empty

        <div class="actualizacion-tarjeta">
            <p>
                No hay solicitudes de actualización pendientes.
            </p>
        </div>

    @endforelse

    @if($solicitudes->hasPages())
        {{ $solicitudes->links() }}
    @endif

</div>

@endsection
