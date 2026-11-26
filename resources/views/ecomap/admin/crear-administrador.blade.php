@extends('ecomap.layout')

@section('title', 'Nuevo administrador - EcoMap')

@section('content')

<style>
    .admin-formulario {
        max-width: 550px;
        margin: 40px auto;
        padding: 35px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px #00000018;
    }

    .admin-formulario h1 {
        color: #237a43;
        margin-bottom: 10px;
    }

    .admin-formulario p {
        color: #666;
        margin-bottom: 25px;
    }

    .admin-campo {
        margin-bottom: 20px;
    }

    .admin-campo label {
        display: block;
        font-weight: bold;
        margin-bottom: 8px;
    }

    .admin-campo input {
        width: 100%;
        box-sizing: border-box;
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 15px;
    }

    .admin-guardar {
        width: 100%;
        background: #237a43;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 14px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
    }

    .admin-guardar:hover {
        background: #195d32;
    }

    .admin-error {
        color: #b91c1c;
        font-size: 14px;
        margin-top: 6px;
    }

    .admin-volver {
        display: inline-block;
        margin-top: 20px;
        color: #237a43;
        text-decoration: none;
    }
</style>

<div class="admin-formulario">

    <h1>Registrar nuevo administrador</h1>

    <p>
        Completa los datos para crear una
        nueva cuenta administrativa en EcoMap.
    </p>

    <form method="POST"
          action="{{ route('admin.administradores.store') }}">

        @csrf

        <div class="admin-campo">
            <label for="name">Nombre completo</label>

            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                required
                maxlength="255"
            >

            @error('name')
                <div class="admin-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="admin-campo">
            <label for="email">Correo electrónico</label>

            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
                maxlength="255"
            >

            @error('email')
                <div class="admin-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="admin-campo">
            <label for="password">Contraseña</label>

            <input
                type="password"
                id="password"
                name="password"
                minlength="12"
                autocomplete="new-password"
                required
            >

            @error('password')
                <div class="admin-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="admin-campo">
            <label for="password_confirmation">
                Confirmar contraseña
            </label>

            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                minlength="12"
                autocomplete="new-password"
                required
            >
        </div>

        <button type="submit" class="admin-guardar">
            Registrar administrador
        </button>

    </form>

    <a href="{{ route('admin.dashboard') }}"
       class="admin-volver">
        Volver al panel de administración
    </a>

</div>

@endsection
