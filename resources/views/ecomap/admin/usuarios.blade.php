@extends('ecomap.layout')

@section('title', 'Usuarios registrados - EcoMap')

@section('content')

<style>
    .usuarios-panel {
        max-width: 1200px;
        margin: 40px auto;
        padding: 20px;
    }

    .usuarios-panel h1 {
        color: #237a43;
        margin-bottom: 10px;
    }

    .usuarios-panel > p {
        color: #666;
        margin-bottom: 25px;
    }

    .usuarios-tabla-contenedor {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 12px #00000015;
        overflow-x: auto;
    }

    .usuarios-tabla {
        width: 100%;
        border-collapse: collapse;
    }

    .usuarios-tabla th {
        background: #237a43;
        color: white;
        padding: 14px;
        text-align: left;
    }

    .usuarios-tabla td {
        padding: 14px;
        border-bottom: 1px solid #ddd;
    }

    .usuarios-tabla tr:hover {
        background: #f1f8f2;
    }

    .usuarios-rol {
        background: #e4f4e8;
        color: #237a43;
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: bold;
    }

    .usuarios-volver {
        display: inline-block;
        margin-bottom: 20px;
        color: #237a43;
        text-decoration: none;
        font-weight: bold;
    }

    .usuarios-paginacion {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-top: 20px;
    }

    .usuarios-paginacion a {
        color: #237a43;
        font-weight: bold;
    }
</style>

<div class="usuarios-panel">

    <a href="{{ route('admin.dashboard') }}"
       class="usuarios-volver">
        ← Volver a Administración
    </a>

    <h1>Usuarios registrados</h1>

    <p>
        Consulta los ciudadanos y dueños de centros
        que tienen una cuenta registrada en EcoMap.
    </p>

    <div class="usuarios-tabla-contenedor">

@if(session('success'))
    <p style="color:green;">{{ session('success') }}</p>
@endif

@if(session('error'))
    <p style="color:red;">{{ session('error') }}</p>
@endif

@if($errors->any())
    <p style="color:red;">Selecciona al menos un usuario válido.</p>
@endif

<form method="POST"
      action="{{ route('admin.usuarios.baja') }}"
      onsubmit="return confirm('¿Seguro que deseas dar de baja a los usuarios seleccionados?');">
    @csrf

        <table class="usuarios-tabla">

            <thead>
                <tr>

                    <th>Seleccionar</th>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo electrónico</th>
                    <th>Tipo de usuario</th>
                    <th>Fecha de registro</th>
                </tr>
            </thead>

            <tbody>
                @forelse($usuarios as $usuario)
                    <tr>

<td>
    <input type="checkbox"
           name="usuarios[]"
           value="{{ $usuario->id }}">
</td>

                        <td>{{ $usuario->id }}</td>

                        <td>{{ $usuario->name }}</td>

                        <td>{{ $usuario->email }}</td>

                        <td>
                            <span class="usuarios-rol">
                                {{ ucfirst($usuario->rol) }}
                            </span>
                        </td>

                        <td>
                            {{ $usuario->created_at?->format('d/m/Y') ?? 'No disponible' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center;">
                            No hay ciudadanos ni dueños registrados.
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

        @if($usuarios->hasPages())
            <div class="usuarios-paginacion">
                <div>
                    @if($usuarios->onFirstPage())
                        <span>Anterior</span>
                    @else
                        <a href="{{ $usuarios->previousPageUrl() }}">Anterior</a>
                    @endif
                </div>

                <span>
                    Página {{ $usuarios->currentPage() }}
                    de {{ $usuarios->lastPage() }}
                </span>

                <div>
                    @if($usuarios->hasMorePages())
                        <a href="{{ $usuarios->nextPageUrl() }}">Siguiente</a>
                    @else
                        <span>Siguiente</span>
                    @endif
                </div>
            </div>
        @endif

    </div>

</div>

@endsection
