@extends('ecomap.layout')
@section('title', 'EcoMap | Nueva contraseña')
@section('content')
<section class="usuarios-wrap">
    <div class="usuarios-card">
        <span class="tag">RECUPERAR ACCESO</span>
        <h1>Establecer nueva contraseña</h1>
        <form method="POST" action="{{ route('password.update') }}" class="usuarios-form">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <input id="email" name="email" type="email" value="{{ old('email', $request->email) }}" required>
                @error('email') <small class="usuarios-campo-error">{{ $message }}</small> @enderror
            </div>
            <div class="form-group">
                <label for="password">Nueva contraseña</label>
                <input id="password" name="password" type="password" autocomplete="new-password" required minlength="8">
                @error('password') <small class="usuarios-campo-error">{{ $message }}</small> @enderror
            </div>
            <div class="form-group">
                <label for="password_confirmation">Confirmar contraseña</label>
                <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required minlength="8">
            </div>
            <button class="btn-primary usuarios-boton" type="submit">Guardar contraseña</button>
        </form>
    </div>
</section>
@endsection
