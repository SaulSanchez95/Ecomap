@extends('ecomap.layout')
@section('title', 'EcoMap | Mi perfil')
@section('content')
<section class="usuarios-wrap">
    <div class="usuarios-card usuarios-card-ancho">
        <span class="tag">MI CUENTA</span>
        <h1>Mi perfil</h1>
        <p class="usuarios-descripcion">Consulta y actualiza tu información personal.</p>
        @if (session('status') === 'perfil-actualizado')
            <div class="usuarios-aviso" role="status">Tus datos se guardaron correctamente.</div>
        @elseif (session('status') === 'password-updated')
            <div class="usuarios-aviso" role="status">Tu contraseña se actualizó correctamente.</div>
        @endif
        <form method="POST" action="{{ route('perfil.actualizar') }}" class="usuarios-form">
            @csrf
            @method('PATCH')
            <div class="form-grid">
                <div class="form-group">
                    <label for="name">Nombre completo</label>
                    <input id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required maxlength="255">
                    @error('name') <small class="usuarios-campo-error">{{ $message }}</small> @enderror
                </div>
                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <input id="email" name="email" type="email" value="{{ old('email', auth()->user()->email) }}" required>
                    @error('email') <small class="usuarios-campo-error">{{ $message }}</small> @enderror
                </div>
                <div class="form-group">
                    <label for="telefono">Teléfono</label>
                    <input id="telefono" name="telefono" type="tel" maxlength="20" value="{{ old('telefono', auth()->user()->telefono) }}">
                    @error('telefono') <small class="usuarios-campo-error">{{ $message }}</small> @enderror
                </div>
                <div class="form-group">
                    <label for="rol">Tipo de cuenta (no editable)</label>
                    <input id="rol" value="{{ match(auth()->user()->rol) { 'ciudadano' => 'Ciudadano', 'dueno' => 'Dueño de centro', 'administrador' => 'Administrador', default => 'Sin rol' } }}" disabled>
                </div>
            </div>
            <button type="submit" class="btn-primary usuarios-boton">Guardar cambios</button>
        </form>

        <hr class="usuarios-separador">
        <h2>Cambiar contraseña</h2>
        <form method="POST" action="{{ url('/user/password') }}" class="usuarios-form">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="current_password">Contraseña actual</label>
                <input id="current_password" name="current_password" type="password" autocomplete="current-password" required>
                @error('current_password', 'updatePassword') <small class="usuarios-campo-error">{{ $message }}</small> @enderror
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label for="new_password">Nueva contraseña</label>
                    <input id="new_password" name="password" type="password" autocomplete="new-password" minlength="8" required>
                    @error('password', 'updatePassword') <small class="usuarios-campo-error">{{ $message }}</small> @enderror
                </div>
                <div class="form-group">
                    <label for="new_password_confirmation">Confirmar nueva contraseña</label>
                    <input id="new_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" minlength="8" required>
                </div>
            </div>
            <button class="btn-secondary usuarios-boton" type="submit">Actualizar contraseña</button>
        </form>
    </div>
</section>
@endsection
