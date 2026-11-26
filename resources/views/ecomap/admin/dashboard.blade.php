
@extends('ecomap.layout')

@section('title', 'Administración - EcoMap')

@section('content')

<style>
    .admin-panel {
        max-width: 1200px;
        margin: 40px auto;
        padding: 25px;
    }

    .admin-panel h1 {
        color: #237a43;
        margin-bottom: 10px;
    }

    .admin-descripcion {
        color: #666;
        margin-bottom: 30px;
    }

    .admin-estadisticas {
        display: grid;
        grid-template-columns: repeat(
            auto-fit, minmax(220px, 1fr)
        );
        gap: 20px;
    }

    .admin-tarjeta {
        background: white;
        border-radius: 12px;
        padding: 30px;
        text-align: center;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .admin-tarjeta h3 {
        color: #444;
        margin-bottom: 15px;
    }

    .admin-numero {
        font-size: 40px;
        font-weight: bold;
        color: #237a43;
    }
</style>

<div class="admin-panel">

    <h1>Panel de Administración</h1>

    <p class="admin-descripcion">
        Bienvenido al sistema administrativo de EcoMap.
        Desde aquí podrás supervisar y gestionar
        la información de la plataforma.
    </p>

<a href="{{ route('admin.centros.pendientes') }}"
   style="display:inline-block;
          background:#237a43;
          color:white;
          padding:12px 22px;
          border-radius:8px;
          text-decoration:none;
          margin-bottom:25px;
          font-weight:bold;">
    Ver centros pendientes de aprobación
</a>

@if(Route::has('admin.administradores.create'))
    <a href="{{ route('admin.administradores.create') }}"
       style="display:inline-block;
              background:#237a43;
              color:white;
              padding:12px 22px;
              border-radius:8px;
              text-decoration:none;
              margin:0 0 25px 12px;
              font-weight:bold;">
        + Agregar administrador
    </a>
@else
    <span style="display:inline-block;
                 background:#777;
                 color:white;
                 padding:12px 22px;
                 border-radius:8px;
                 margin-left:12px;">
        + Agregar administrador (pendiente)
    </span>
@endif

<!-- Menú de consultas administrativas -->
<div style="display:flex;
            flex-wrap:wrap;
            gap:12px;
            margin-bottom:25px;">

    <a href="{{ route('admin.usuarios.index') }}"
       style="background:#237a43;
              color:white;
              padding:12px 22px;
              border-radius:8px;
              text-decoration:none;
              font-weight:bold;">
        Ver usuarios registrados
    </a>

    <a href="{{ route('admin.administradores.index') }}"
       style="background:#237a43;
              color:white;
              padding:12px 22px;
              border-radius:8px;
              text-decoration:none;
              font-weight:bold;">
        Ver administradores registrados
    </a>

    <a href="{{ route('admin.centros.index') }}"
       style="background:#237a43;
              color:white;
              padding:12px 22px;
              border-radius:8px;
              text-decoration:none;
              font-weight:bold;">
        Ver centros de acopio
    </a>

</div>

<!-- Acceso a solicitudes de actualización -->
<div style="margin-bottom:25px;">
    <a href="{{ route('admin.actualizaciones.index') }}"
       style="display:inline-block;
              background:#237a43;
              color:white;
              padding:12px 22px;
              border-radius:8px;
              text-decoration:none;
              font-weight:bold;">
        Ver solicitudes de actualización
    </a>
</div>

    <div class="admin-estadisticas">

        <div class="admin-tarjeta">
            <h3>Usuarios registrados</h3>
            <div class="admin-numero">
                {{ $totalUsuarios }}
            </div>
        </div>

        <div class="admin-tarjeta">
            <h3>Centros de acopio</h3>
            <div class="admin-numero">
                {{ $totalCentros }}
            </div>
        </div>

        <div class="admin-tarjeta">
            <h3>Centros pendientes</h3>
            <div class="admin-numero">
                {{ $centrosPendientes }}
            </div>
        </div>

    </div>

</div>

@endsection
