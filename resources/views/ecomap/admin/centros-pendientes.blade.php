@extends('ecomap.layout')

@section('title', 'Centros pendientes - EcoMap')

@section('content')

<style>
    .admin-contenedor {
        max-width: 1200px;
        margin: 40px auto;
        padding: 20px;
    }

    .admin-contenedor h1 {
        color: #237a43;
        margin-bottom: 12px;
    }

    .centro-tarjeta {
        background: white;
        padding: 25px;
        border-radius: 12px;
        margin-top: 20px;
        box-shadow: 0 4px 12px #00000015;
    }

    .centro-tarjeta h2 {
        color: #237a43;
    }

    .centro-datos {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 15px;
        margin-top: 20px;
    }

    .centro-datos p {
        margin: 8px 0;
        overflow-wrap: anywhere;
    }

    .centro-detalles {
        margin-top: 15px;
    }

    .centro-detalles summary {
        cursor: pointer;
        color: #237a43;
        font-weight: bold;
    }

    .estado-pendiente {
        color: #a16207;
        font-weight: bold;
    }
</style>

<div class="admin-contenedor">

    <h1>Centros pendientes de aprobación</h1>

    <p>
        Revisa la información de los centros de acopio
        antes de autorizar su registro en EcoMap.
    </p>

    @forelse($centrosPendientes as $centro)

        <div class="centro-tarjeta">

            <h2>{{ $centro->nombre }}</h2>

            <p class="estado-pendiente">
                Estado: Pendiente de validación
            </p>

            <p>
                <strong>Propietario:</strong>
                {{ $centro->dueno?->name ?? 'No disponible' }}
            </p>

            <details class="centro-detalles">
                <summary>Ver todos los datos del centro</summary>

                <div class="centro-datos">

                    <div>
                        <p><strong>Descripción:</strong>
                            {{ $centro->descripcion ?? 'No registrada' }}
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
                    </div>

                    <div>
                        <p><strong>Teléfono:</strong>
                            {{ $centro->telefono ?? 'No registrado' }}
                        </p>

                        <p><strong>Correo:</strong>
                            {{ $centro->correo ?? 'No registrado' }}
                        </p>

                        <p><strong>Horario:</strong>
                            {{ $centro->horario ?? 'No registrado' }}
                        </p>

                        <p><strong>Latitud:</strong>
                            {{ $centro->latitud ?? 'No registrada' }}
                        </p>

                        <p><strong>Longitud:</strong>
                            {{ $centro->longitud ?? 'No registrada' }}
                        </p>

                        <p><strong>Correo del propietario:</strong>
                            {{ $centro->dueno?->email ?? 'No disponible' }}
                        </p>
                    </div>

                </div>

                <h3>Materiales aceptados</h3>

                @forelse($centro->materiales as $material)
                    <span>{{ $material->nombre }}{{ !$loop->last ? ', ' : '' }}</span>
                @empty
                    <p>No hay materiales registrados.</p>
                @endforelse

                <p>
                    <strong>Fecha de registro:</strong>
                    {{ $centro->created_at?->format('d/m/Y H:i') ?? 'No disponible' }}
                </p>

            </details>

<!-- Acciones del administrador -->
<div style="display:flex;
            flex-wrap:wrap;
            gap:20px;
            margin-top:25px;">

    <!-- Aprobar centro -->
    <form method="POST"
          action="{{ route('admin.centros.aprobar', $centro->id) }}"
          onsubmit="return confirm('¿Seguro que deseas aprobar este centro?');">

        @csrf

        <button type="submit"
                style="background:#237a43;
                       color:white;
                       border:none;
                       border-radius:8px;
                       padding:12px 24px;
                       font-weight:bold;
                       cursor:pointer;">
            ✓ Aprobar centro
        </button>
    </form>

    <!-- Rechazar centro -->
    <form method="POST"
          action="{{ route('admin.centros.rechazar', $centro->id) }}"
          onsubmit="return confirm('¿Seguro que deseas rechazar este centro?');">

        @csrf

        <label for="motivo-centro-{{ $centro->id }}"
               style="display:block;
                      font-weight:bold;
                      margin-bottom:8px;">
            Motivo del rechazo:
        </label>

        <textarea
            id="motivo-centro-{{ $centro->id }}"
            name="observaciones_admin"
            required
            maxlength="1000"
            rows="3"
            placeholder="Explica por qué se rechaza el centro"
            style="display:block;
                   width:100%;
                   min-width:260px;
                   box-sizing:border-box;
                   padding:10px;
                   border:1px solid #ccc;
                   border-radius:8px;
                   margin-bottom:10px;"></textarea>

        <button type="submit"
                style="background:#c62828;
                       color:white;
                       border:none;
                       border-radius:8px;
                       padding:12px 24px;
                       font-weight:bold;
                       cursor:pointer;">
            ✕ Rechazar centro
        </button>
    </form>

</div>

        </div>

    @empty

        <div class="centro-tarjeta">
            <p>No hay centros pendientes de aprobación.</p>
        </div>

    @endforelse

</div>

@endsection
