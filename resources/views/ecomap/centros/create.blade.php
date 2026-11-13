
@extends('ecomap.layout')

@section('title', 'Registrar centro de acopio | EcoMap')

@section('content')

<section class="page-banner">
    <div class="container">
        <h1>Registrar centro de acopio</h1>
        <p>Agrega la información de tu centro para solicitar su incorporación a EcoMap.</p>
    </div>
</section>

<section class="section">
    <div class="container">

        <div class="form-card">
            <h2>Información del centro</h2>

            @if($errors->any())
                <div class="status">
                    <strong>Revisa los siguientes errores:</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('centros.store') }}" method="POST">
                @csrf

                <div class="form-grid">

                    <div class="form-group">
                        <label for="nombre">Nombre del centro *</label>
                        <input
                            type="text"
                            id="nombre"
                            name="nombre"
                            value="{{ old('nombre') }}"
                            maxlength="120"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input
                            type="text"
                            id="telefono"
                            name="telefono"
                            value="{{ old('telefono') }}"
                            maxlength="20"
                        >
                    </div>

                    <div class="form-group">
                        <label for="correo">Correo electrónico</label>
                        <input
                            type="email"
                            id="correo"
                            name="correo"
                            value="{{ old('correo') }}"
                            maxlength="120"
                        >
                    </div>

                    <div class="form-group">
                        <label for="codigo_postal">Código postal</label>
                        <input
                            type="text"
                            id="codigo_postal"
                            name="codigo_postal"
                            value="{{ old('codigo_postal') }}"
                            maxlength="10"
                        >
                    </div>

                    <div class="form-group full">
                        <label for="descripcion">Descripción</label>
                        <textarea
                            id="descripcion"
                            name="descripcion"
                        >{{ old('descripcion') }}</textarea>
                    </div>

                    <div class="form-group full">
                        <label for="direccion">Dirección *</label>
                        <input
                            type="text"
                            id="direccion"
                            name="direccion"
                            value="{{ old('direccion') }}"
                            maxlength="200"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="colonia">Colonia</label>
                        <input
                            type="text"
                            id="colonia"
                            name="colonia"
                            value="{{ old('colonia') }}"
                            maxlength="100"
                        >
                    </div>

                    <div class="form-group">
                        <label for="municipio">Municipio *</label>
                        <input
                            type="text"
                            id="municipio"
                            name="municipio"
                            value="{{ old('municipio') }}"
                            maxlength="100"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="estado">Estado *</label>
                        <input
                            type="text"
                            id="estado"
                            name="estado"
                            value="{{ old('estado', 'Colima') }}"
                            maxlength="100"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="horario">Horario de atención</label>
                        <textarea
                            id="horario"
                            name="horario"
                        >{{ old('horario') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="latitud">Latitud</label>
                        <input
                            type="number"
                            id="latitud"
                            name="latitud"
                            value="{{ old('latitud') }}"
                            step="any"
                            min="-90"
                            max="90"
                        >
                    </div>

                    <div class="form-group">
                        <label for="longitud">Longitud</label>
                        <input
                            type="number"
                            id="longitud"
                            name="longitud"
                            value="{{ old('longitud') }}"
                            step="any"
                            min="-180"
                            max="180"
                        >
                    </div>

                    <div class="form-group full">
                        <label>Materiales que acepta *</label>

                        <p class="section-description">
                            Selecciona todos los materiales que recibe tu centro.
                        </p>

                        @foreach($materiales as $material)
                            <label>
                                <input
                                    type="checkbox"
                                    name="materiales[]"
                                    value="{{ $material->id }}"
                                    {{ in_array($material->id, old('materiales', [])) ? 'checked' : '' }}
                                >
                                {{ $material->nombre }}
                            </label>
                        @endforeach
                    </div>

                </div>

                <br>

                <p class="section-description">
                    Al enviar el formulario, tu centro quedará pendiente
                    de validación. No aparecerá como centro validado hasta
                    que se complete ese proceso.
                </p>

                <br>

                <button type="submit" class="btn-primary">
                    Enviar registro
                </button>

                <a href="{{ route('centros.index') }}" class="btn-secondary">
                    Cancelar
                </a>

            </form>
        </div>

    </div>
</section>

@endsection
