
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

            @if(session('success'))
                <div class="status">
                    {{ session('success') }}
                </div>
            @endif

            @php
                // Identificadores de materiales aceptados por el centro.
                $idsMaterialesAceptados = $campania->centro
                    ? $campania->centro->materiales
                        ->filter(fn ($material) => (bool) $material->activo)
                        ->pluck('id')
                        ->map(fn ($id) => (int) $id)
                        ->all()
                    : [];

                // Datos anteriores enviados cuando hubo errores.
                $materialesAnteriores = old('materiales');
            @endphp

            <form
                action="{{ route('campanias.update', $campania->id) }}"
                method="POST"
            >
                @csrf
                @method('PUT')

                <div class="form-grid">

                    {{-- Título --}}
                    <div class="form-group full">
                        <label for="titulo">
                            Título de la campaña *
                        </label>

                        <input
                            type="text"
                            id="titulo"
                            name="titulo"
                            value="{{ old('titulo', $campania->titulo) }}"
                            maxlength="120"
                            required
                        >
                    </div>

                    {{-- Descripción --}}
                    <div class="form-group full">
                        <label for="descripcion">
                            Descripción
                        </label>

                        <textarea
                            id="descripcion"
                            name="descripcion"
                            rows="4"
                        >{{ old('descripcion', $campania->descripcion) }}</textarea>
                    </div>

                    {{-- Fechas --}}
                    <div class="form-group">
                        <label for="fecha_inicio">
                            Fecha de inicio *
                        </label>

                        <input
                            type="date"
                            id="fecha_inicio"
                            name="fecha_inicio"
                            value="{{ old('fecha_inicio', $campania->fecha_inicio?->format('Y-m-d')) }}"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="fecha_fin">
                            Fecha de finalización *
                        </label>

                        <input
                            type="date"
                            id="fecha_fin"
                            name="fecha_fin"
                            value="{{ old('fecha_fin', $campania->fecha_fin?->format('Y-m-d')) }}"
                            required
                        >
                    </div>

                    {{-- Materiales --}}
                    <div class="form-group full">

                        <h3>Materiales solicitados</h3>

                        <p class="section-description">
                            Selecciona los materiales que necesitas y modifica
                            sus cantidades. Solo puedes agregar materiales
                            aceptados por el centro.
                        </p>

                        <div class="campania-materiales-grid">

                            @foreach($materiales as $material)

                                @php
                                    $materialActual = $campania->materiales
                                        ->firstWhere('id', $material->id);

                                    $materialAnterior = is_array($materialesAnteriores)
                                        ? ($materialesAnteriores[$loop->index] ?? null)
                                        : null;

                                    $esInactivo = $materialActual !== null &&
                                        !(bool) $material->activo;

                                    $esAceptado = in_array(
                                        (int) $material->id,
                                        $idsMaterialesAceptados,
                                        true
                                    );

                                    $seleccionado = $esInactivo
                                        ? true
                                        : (
                                            is_array($materialesAnteriores)
                                                ? (
                                                    isset($materialAnterior['id']) &&
                                                    (int) $materialAnterior['id'] ===
                                                    (int) $material->id
                                                )
                                                : (
                                                    $materialActual !== null &&
                                                    $esAceptado
                                                )
                                        );

                                    $cantidad = old(
                                        'materiales.'.$loop->index.'.cantidad',
                                        $materialActual?->pivot->cantidad_solicitada
                                    );
                                @endphp

                                <div
                                    class="campania-material-card"
                                    data-material-id="{{ $material->id }}"
                                    data-aceptado="{{ $esAceptado ? '1' : '0' }}"
                                    data-inactivo="{{ $esInactivo ? '1' : '0' }}"
                                    style="{{ ($esAceptado || $esInactivo) ? '' : 'display:none;' }}"
                                >

                                    <label
                                        class="campania-material-opcion"
                                        for="material_{{ $material->id }}"
                                    >
                                        <input
                                            type="checkbox"
                                            id="material_{{ $material->id }}"
                                            name="materiales[{{ $loop->index }}][id]"
                                            value="{{ $material->id }}"
                                            {{ $seleccionado ? 'checked' : '' }}
                                            {{ $esInactivo || !$esAceptado ? 'disabled' : '' }}
                                        >

                                        <span>
                                            {{ $material->nombre }}

                                            @if($esInactivo)
                                                <span class="campania-material-inactivo">
                                                    (Inactivo, conservado en historial)
                                                </span>
                                            @endif
                                        </span>
                                    </label>

                                    <label for="cantidad_{{ $material->id }}">
                                        Cantidad solicitada
                                        ({{ $material->unidad_medida }})
                                    </label>

                                    <input
                                        type="number"
                                        id="cantidad_{{ $material->id }}"
                                        name="materiales[{{ $loop->index }}][cantidad]"
                                        value="{{ $cantidad }}"
                                        min="0.001"
                                        max="999999999.999"
                                        step="0.001"
                                        {{ $esInactivo || !$seleccionado ? 'disabled' : 'required' }}
                                    >

                                </div>

                            @endforeach

                        </div>

                        <p
                            id="campania-sin-materiales"
                            class="section-description"
                            style="display:none;"
                        >
                            No hay materiales activos aceptados por este centro.
                        </p>

                        <p class="section-description">
                            Los materiales inactivos que ya estaban asociados
                            se conservarán en el historial de la campaña.
                        </p>

                    </div>

                </div>

                <div class="campania-acciones">

                    <button type="submit" class="btn-primary">
                        Guardar cambios
                    </button>

                    <a
                        href="{{ route('campanias.show', $campania->id) }}"
                        class="btn-secondary"
                    >
                        Cancelar
                    </a>

                </div>

            </form>

        </div>

    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const tarjetas = document.querySelectorAll(
        '.campania-material-card'
    );

    const mensajeSinMateriales = document.getElementById(
        'campania-sin-materiales'
    );

    const fechaInicio = document.getElementById('fecha_inicio');
    const fechaFin = document.getElementById('fecha_fin');

    // Mostrar los materiales aceptados y conservar los inactivos anteriores.
    let materialesDisponibles = 0;

    tarjetas.forEach(function (tarjeta) {

        const aceptado = tarjeta.dataset.aceptado === '1';
        const inactivo = tarjeta.dataset.inactivo === '1';

        const checkbox = tarjeta.querySelector(
            'input[type="checkbox"]'
        );

        const cantidad = tarjeta.querySelector(
            'input[type="number"]'
        );

        const visible = aceptado || inactivo;

        tarjeta.style.display = visible ? 'grid' : 'none';

        if (inactivo) {

            // Los materiales inactivos asociados se conservan,
            // pero no pueden modificarse ni volver a seleccionarse.
            checkbox.checked = true;
            checkbox.disabled = true;

            cantidad.disabled = true;
            cantidad.required = false;

        } else if (!aceptado) {

            checkbox.checked = false;
            cantidad.value = '';
            cantidad.disabled = true;
            cantidad.required = false;

        } else {

            materialesDisponibles++;

            checkbox.disabled = false;
            cantidad.disabled = !checkbox.checked;
            cantidad.required = checkbox.checked;
        }

        if (aceptado) {
            checkbox.addEventListener('change', function () {

                cantidad.disabled = !this.checked;
                cantidad.required = this.checked;

                if (!this.checked) {
                    cantidad.value = '';
                }
            });
        }
    });

    if (mensajeSinMateriales) {
        mensajeSinMateriales.style.display =
            materialesDisponibles === 0 ? 'block' : 'none';
    }

    // La fecha final no puede ser anterior a la fecha inicial.
    function actualizarFechaMinima() {

        if (!fechaInicio || !fechaFin) {
            return;
        }

        fechaFin.min = fechaInicio.value;

        if (
            fechaInicio.value &&
            fechaFin.value &&
            fechaFin.value < fechaInicio.value
        ) {
            fechaFin.value = '';
        }
    }

    if (fechaInicio && fechaFin) {
        fechaInicio.addEventListener('change', actualizarFechaMinima);
        actualizarFechaMinima();
    }
});
</script>

@endsection
