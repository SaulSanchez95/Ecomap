@extends('ecomap.layout')
@section('title', 'EcoMap | Iniciar sesión')
@section('content')
<section class="usuarios-wrap">
    <div class="usuarios-card">
        <span class="tag">TU CUENTA ECOMAP</span>
        <h1>Iniciar sesión</h1>
        <p class="usuarios-descripcion">Continúa reciclando y administrando tus actividades.</p>

        @if (session('status'))
            <div class="usuarios-aviso" role="status">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div class="usuarios-error" role="alert">Revisa tus datos e inténtalo de nuevo.</div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="usuarios-form">
            @csrf
            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <input id="email" name="email" type="email" autocomplete="username" value="{{ old('email') }}" required autofocus>
                @error('email') <small class="usuarios-campo-error">{{ $message }}</small> @enderror
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input id="password" name="password" type="password" autocomplete="current-password" required>
                @error('password') <small class="usuarios-campo-error">{{ $message }}</small> @enderror
            </div>
            <label class="usuarios-check"><input type="checkbox" name="remember" value="1"> Recordar sesión</label>
            <button type="submit" class="btn-primary usuarios-boton">Entrar</button>
        </form>
        <p class="usuarios-links"><a href="{{ route('password.request') }}">Olvidé mi contraseña</a></p>
        <p class="usuarios-links">¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate</a></p>
    </div>
</section>
@endsection
