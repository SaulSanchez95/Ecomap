@extends('ecomap.layout')

@section('title', 'Administradores registrados - EcoMap')

@section('content')

<style>
    .administradores-panel {
        max-width: 1200px;
        margin: 40px auto;
        padding: 20px;
    }

    .administradores-panel h1 {
        color: #237a43;
        margin-bottom: 12px;
    }

    .administradores-descripcion {
        color: #666;
        margin-bottom: 25px;
    }

    .administradores-tabla-contenedor {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 12px #00000015;
        overflow-x: auto;
    }

    .administradores-tabla {
        width: 100%;
        border-collapse: collapse;
    }

    .administradores-tabla th {
        background: #237a43;
        color: white;
        padding: 14px;
        text-align: left;
    }

    .administradores-tabla td {
        padding: 14px;
        border-bottom: 1px solid #ddd;
    }

    .administradores-tabla tr:hover {
        background: #f1f8f2;
    }

    .administradores-rol {
        display: inline-block;
        background: #e4f4e8;
        color: #237a43;
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: bold;
    }

    .administradores-volver {
        display: inline-block;
        margin-bottom: 20px;
        color: #237a43;
        text-decoration: none;
        font-weight: bold;
    }

    .administradores-paginacion {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        margin-top: 20px;
    }

    .administradores-paginacion a {
        color: #237a43;
        font-weight: bold;
    }
</style>

<div class="administradores-panel">

    <a href="{{ route('admin.dashboard') }}"
       class="administradores-volver">
        ← Volver a Administración
    </a>

    <h1>Administradores registrados</h1>

    <p class="administradores-descripcion">
        Consulta las cuentas que tienen permisos
        administrativos dentro de EcoMap.
    </p>

    <div class="administradores-tabla-contenedor">

@if(session('success'))
    <p style="color:green;">{{ session('success') }}</p>
@endif

@if(session('error'))
    <p style="color:red;">{{ session('error') }}</p>
@endif

@if($errors->any())
    <p style="color:red;">
        Selecciona al menos un administrador válido.
    </p>
@endif

<form method="POST"
      action="{{ route('admin.administradores.baja') }}"
      onsubmit="return confirm('¿Seguro que deseas dar de baja a los administradores seleccionados?');">
    @csrf

        <table class="administradores-tabla">

            <thead>
                <tr>

<th>Seleccionar</th>

                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo electrónico</th>
                    <th>Rol</th>
                    <th>Fecha de registro</th>
                </tr>
            </thead>

            <tbody>
                @forelse($administradores as $administrador)
                    <tr>

<td>
    <input type="checkbox"
           name="administradores[]"
           value="{{ $administrador->id }}"
           @if($administrador->id === auth()->id()) disabled @endif>
</td>

                        <td>{{ $administrador->id }}</td>

                        <td>{{ $administrador->name }}</td>

                        <td>{{ $administrador->email }}</td>

                        <td>
                            <span class="administradores-rol">
                                Administrador
                            </span>
                        </td>

                        <td>
                            {{ $administrador->created_at?->format('d/m/Y') ?? 'No disponible' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center;">
                            No hay administradores registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>

<div style="margin-top:20px;">
    <button type="submit"
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

        @if($administradores->hasPages())
            <div class="administradores-paginacion">

                @if($administradores->onFirstPage())
                    <span>Anterior</span>
                @else
                    <a href="{{ $administradores->previousPageUrl() }}">
                        Anterior
                    </a>
                @endif

                <span>
                    Página {{ $administradores->currentPage() }}
                    de {{ $administradores->lastPage() }}
                </span>

                @if($administradores->hasMorePages())
                    <a href="{{ $administradores->nextPageUrl() }}">
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
