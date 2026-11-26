@extends('ecomap.layout')
@section('title', 'EcoMap | Crear cuenta')
@section('content')
<section class="usuarios-wrap">
    <div class="usuarios-card usuarios-card-ancho">
        <span class="tag">ÚNETE A ECOMAP</span>
        <h1>Crear cuenta</h1>
        <p class="usuarios-descripcion">Regístrate como ciudadano o como dueño de un centro de acopio.</p>

        @if ($errors->any())
            <div class="usuarios-error" role="alert">Revisa los campos marcados.</div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="usuarios-form">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label for="name">Nombre completo</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" maxlength="255" autocomplete="name" required>
                    @error('name') <small class="usuarios-campo-error">{{ $message }}</small> @enderror
                </div>
                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" maxlength="255" autocomplete="email" required>
                    @error('email') <small class="usuarios-campo-error">{{ $message }}</small> @enderror
                </div>
                <div class="form-group">
                    <label for="telefono">Teléfono (opcional)</label>
                    <input id="telefono" name="telefono" type="tel" maxlength="20" value="{{ old('telefono') }}" autocomplete="tel">
                    @error('telefono') <small class="usuarios-campo-error">{{ $message }}</small> @enderror
                </div>
                <div class="form-group">
                    <label for="rol">Tipo de cuenta</label>
                    <select id="rol" name="rol" required>
                        <option value="">Selecciona tu tipo de cuenta</option>
                        <option value="ciudadano" @selected(old('rol') === 'ciudadano')>Ciudadano</option>
                        <option value="dueno" @selected(old('rol') === 'dueno')>Dueño de centro de acopio</option>
                    </select>
                    @error('rol') <small class="usuarios-campo-error">{{ $message }}</small> @enderror
                </div>
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input id="password" name="password" type="password" autocomplete="new-password" minlength="8" required>
                    @error('password') <small class="usuarios-campo-error">{{ $message }}</small> @enderror
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirmar contraseña</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" minlength="8" required>
                </div>
            </div>
            <button type="submit" class="btn-primary usuarios-boton">Crear cuenta</button>
        </form>
        <p class="usuarios-links">¿Ya tienes una cuenta? <a href="{{ route('login') }}">Inicia sesión</a></p>
    </div>
</section>
@endsection
