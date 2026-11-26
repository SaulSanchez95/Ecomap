
@extends('ecomap.layout')

@section('title', 'Solicitar actualización | EcoMap')

@section('content')

<section class="page-banner">
    <div class="container">
        <h1>Solicitar actualización</h1>
        <p>Propón cambios en la información de tu centro de acopio.</p>
    </div>
</section>

<section class="section">
    <div class="container">

        <div class="form-card">
            <h2>{{ $centro->nombre }}</h2>

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

            <form action="{{ route('centros.update', $centro->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-grid">

                    <div class="form-group">
                        <label for="nombre">Nombre del centro *</label>
                        <input type="text" id="nombre" name="nombre"
                            value="{{ old('nombre', $centro->nombre) }}"
                            maxlength="120" required>
                    </div>

                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="text" id="telefono" name="telefono"
                            value="{{ old('telefono', $centro->telefono) }}"
                            maxlength="20">
                    </div>

                    <div class="form-group">
                        <label for="correo">Correo electrónico</label>
                        <input type="email" id="correo" name="correo"
                            value="{{ old('correo', $centro->correo) }}"
                            maxlength="120">
                    </div>

                    <div class="form-group">
                        <label for="codigo_postal">Código postal</label>
                        <input type="text" id="codigo_postal" name="codigo_postal"
                            value="{{ old('codigo_postal', $centro->codigo_postal) }}"
                            maxlength="10">
                    </div>

                    <div class="form-group full">
                        <label for="descripcion">Descripción</label>
                        <textarea id="descripcion" name="descripcion">{{ old('descripcion', $centro->descripcion) }}</textarea>
                    </div>

                    <div class="form-group full">
                        <label for="direccion">Dirección *</label>
                        <input type="text" id="direccion" name="direccion"
                            value="{{ old('direccion', $centro->direccion) }}"
                            maxlength="200" required>
                    </div>

                    <div class="form-group">
                        <label for="colonia">Colonia</label>
                        <input type="text" id="colonia" name="colonia"
                            value="{{ old('colonia', $centro->colonia) }}"
                            maxlength="100">
                    </div>

                    <div class="form-group">
                        <label for="municipio">Municipio *</label>
                        <input type="text" id="municipio" name="municipio"
                            value="{{ old('municipio', $centro->municipio) }}"
                            maxlength="100" required>
                    </div>

                    <div class="form-group">
                        <label for="estado">Estado *</label>
                        <input type="text" id="estado" name="estado"
                            value="{{ old('estado', $centro->estado) }}"
                            maxlength="100" required>
                    </div>

                    <div class="form-group">
                        <label for="horario">Horario de atención</label>
                        <textarea id="horario" name="horario">{{ old('horario', $centro->horario) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="latitud">Latitud</label>
                        <input type="number" id="latitud" name="latitud"
                            value="{{ old('latitud', $centro->latitud) }}"
                            step="any" min="-90" max="90">
                    </div>

                    <div class="form-group">
                        <label for="longitud">Longitud</label>
                        <input type="number" id="longitud" name="longitud"
                            value="{{ old('longitud', $centro->longitud) }}"
                            step="any" min="-180" max="180">
                    </div>

                    <div class="form-group full">
                        <label>Materiales que acepta *</label>

                        <p class="section-description">
                            Selecciona los materiales que recibirá el centro.
                        </p>

                        @php
                            $materialesSeleccionados = old(
                                'materiales',
                                $centro->materiales->pluck('id')->toArray()
                            );
                        @endphp

                        @foreach($materiales as $material)
                            <label>
                                <input
                                    type="checkbox"
                                    name="materiales[]"
                                    value="{{ $material->id }}"
                                    {{ in_array($material->id, $materialesSeleccionados) ? 'checked' : '' }}
                                >
                                {{ $material->nombre }}
                            </label>
                        @endforeach
                    </div>

                </div>

                <br>

                <p class="section-description">
                    Los cambios propuestos no se aplicarán inmediatamente.
                    Primero deberán ser revisados y aprobados por un administrador.
                </p>

                <br>

                <button type="submit" class="btn-primary">
                    Enviar solicitud de actualización
                </button>

                <a href="{{ route('centros.show', $centro->id) }}"
                   class="btn-secondary">
                    Cancelar
                </a>

            </form>
        </div>

    </div>
</section>

@endsection
