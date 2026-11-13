@extends('ecomap.layout')

@section('title', 'Editar campaña | EcoMap')

@section('content')

<section class="page-banner">
    <div class="container">
        <h1>Editar campaña</h1>
        <p>Modifica la información de tu campaña de reciclaje.</p>
    </div>
</section>

<section class="section">
    <div class="container">

        <div class="form-card">

            <h2>{{ $campania->titulo }}</h2>

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

            <form action="{{ route('campanias.update', $campania->id) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="form-grid">

                    <div class="form-group full">
                        <label for="titulo">Título de la campaña *</label>
                        <input
                            type="text"
                            id="titulo"
                            name="titulo"
                            value="{{ old('titulo', $campania->titulo) }}"
                            maxlength="120"
                            required
                        >
                    </div>

                    <div class="form-group full">
                        <label for="descripcion">Descripción</label>
                        <textarea
                            id="descripcion"
                            name="descripcion"
                        >{{ old('descripcion', $campania->descripcion) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="fecha_inicio">Fecha de inicio *</label>
                        <input
                            type="date"
                            id="fecha_inicio"
                            name="fecha_inicio"
                            value="{{ old('fecha_inicio', $campania->fecha_inicio?->format('Y-m-d')) }}"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="fecha_fin">Fecha de finalización *</label>
                        <input
                            type="date"
                            id="fecha_fin"
                            name="fecha_fin"
                            value="{{ old('fecha_fin', $campania->fecha_fin?->format('Y-m-d')) }}"
                            required
                        >
                    </div>

                    <div class="form-group full">
                        <h3>Materiales solicitados</h3>
                        <p class="section-description">
                            Marca los materiales que necesitas y escribe
                            la cantidad solicitada.
                        </p>
                    </div>

                    @foreach($materiales as $material)

                        @php
                            $materialActual = $campania->materiales->firstWhere('id', $material->id);

                            $materialesAnteriores = old('materiales');

                            $seleccionado = is_array($materialesAnteriores)
                                ? isset($materialesAnteriores[$loop->index]['id'])
                                : $materialActual !== null;

                            $esInactivo = !$material->activo && $materialActual !== null;

                            $cantidad = old(
                                'materiales.'.$loop->index.'.cantidad',
                                $materialActual?->pivot->cantidad_solicitada
                            );
                        @endphp

                        <div class="form-group">

                            <label for="material_{{ $material->id }}">
                                <input
                                    type="checkbox"
                                    id="material_{{ $material->id }}"
                                    name="materiales[{{ $loop->index }}][id]"
                                    value="{{ $material->id }}"
                                    {{ $seleccionado ? 'checked' : '' }}
                                    {{ $esInactivo ? 'disabled' : '' }}
                                    onchange="
                                        const cantidad = document.getElementById('cantidad_{{ $material->id }}');
                                        cantidad.disabled = !this.checked;
                                        cantidad.required = this.checked;

                                        if (!this.checked) {
                                            cantidad.value = '';
                                        }
                                    "
                                >

                                {{ $material->nombre }}

                                @if($esInactivo)
                                    <span style="color: #b45309; font-weight: bold;">
                                        (Inactivo, conservado en historial)
                                    </span>
                                @endif
                            </label>

                            <label for="cantidad_{{ $material->id }}">
                                Cantidad solicitada ({{ $material->unidad_medida }})
                            </label>

                            <input
                                type="number"
                                id="cantidad_{{ $material->id }}"
                                name="materiales[{{ $loop->index }}][cantidad]"
                                value="{{ $cantidad }}"
                                min="0.001"
                                max="999999999.999"
                                step="0.001"
                                {{ $seleccionado && !$esInactivo ? 'required' : 'disabled' }}
                            >

                        </div>

                    @endforeach

                </div>

                <br>

                <button type="submit" class="btn-primary">
                    Guardar cambios
                </button>

                <a href="{{ route('campanias.show', $campania->id) }}"
                   class="btn-secondary">
                    Cancelar
                </a>

            </form>

        </div>

    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const fechaInicio = document.getElementById('fecha_inicio');
    const fechaFin = document.getElementById('fecha_fin');

    function actualizarFechaMinima() {
        fechaFin.min = fechaInicio.value;

        if (
            fechaInicio.value &&
            fechaFin.value &&
            fechaFin.value < fechaInicio.value
        ) {
            fechaFin.value = '';
        }
    }

    fechaInicio.addEventListener('change', actualizarFechaMinima);

    actualizarFechaMinima();
});
</script>

@endsection
