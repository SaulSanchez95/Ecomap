@extends('ecomap.layout')
@section('title', 'EcoMap | Recuperar contraseña')
@section('content')
<section class="usuarios-wrap">
    <div class="usuarios-card">
        <span class="tag">RECUPERAR ACCESO</span>
        <h1>¿Olvidaste tu contraseña?</h1>
        <p class="usuarios-descripcion">Te enviaremos un enlace de recuperación a tu correo electrónico.</p>
        @if (session('status')) <div class="usuarios-aviso" role="status">{{ session('status') }}</div> @endif
        <form method="POST" action="{{ route('password.email') }}" class="usuarios-form">
            @csrf
            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required>
                @error('email') <small class="usuarios-campo-error">{{ $message }}</small> @enderror
            </div>
            <button class="btn-primary usuarios-boton" type="submit">Enviar enlace</button>
        </form>
        <p class="usuarios-links"><a href="{{ route('login') }}">Volver a iniciar sesión</a></p>
    </div>
</section>
@endsection
