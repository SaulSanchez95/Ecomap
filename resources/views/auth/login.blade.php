
@extends('ecomap.layout')

@section('title', 'Iniciar sesión - EcoMap')

@section('content')
<div style="max-width:420px; margin:50px auto; padding:30px;
            background:white; border-radius:12px;
            box-shadow:0 4px 12px #0002;">

    <h1 style="color:#237a43;">Iniciar sesión</h1>
    <p>Accede a tu cuenta de EcoMap.</p>

    @if ($errors->any())
        <p style="color:#b91c1c;">
            Revisa tu correo y contraseña.
        </p>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <label for="email">Correo electrónico</label>
        <input type="email" id="email" name="email"
               value="{{ old('email') }}"
               autocomplete="email" required
               style="width:100%; padding:12px;
                      margin:8px 0 20px;">

        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password"
               autocomplete="current-password" required
               style="width:100%; padding:12px;
                      margin:8px 0 20px;">

        <button type="submit"
                style="width:100%; padding:12px;
                       background:#237a43; color:white;
                       border:none; border-radius:8px;">
            Iniciar sesión
        </button>
    </form>
</div>
@endsection
