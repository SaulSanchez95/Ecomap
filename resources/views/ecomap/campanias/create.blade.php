
@extends('ecomap.layout')

@section('title', 'Crear campaña | EcoMap')

@section('content')

<section class="page-banner">
    <div class="container">
        <h1>Crear campaña</h1>
        <p>Registra una nueva campaña para tu centro de acopio.</p>
    </div>
</section>

<section class="section">
    <div class="container">

        <div class="form-card">

            <h2>Información de la campaña</h2>

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

            @if($centros->isEmpty())

                <div class="status">
                    No tienes centros activos y validados para crear campañas.
                </div>

                <a href="{{ route('centros.index') }}" class="btn-secondary">
                    Volver a mis centros
                </a>

            @else

                <form
                    action="{{ route('campanias.store') }}"
                    method="POST"
                    id="form-campania"
                >
                    @csrf

                    <div class="form-grid">

                        {{-- Centro de acopio --}}
                        <div class="form-group full">
                            <label for="fk_id_centro">
                                Centro de acopio *
                            </label>

                            <select
                                id="fk_id_centro"
                                name="fk_id_centro"
                                required
                            >
                                <option value="">
                                    Selecciona un centro
                                </option>

                                @foreach($centros as $centro)
                                    <option
                                        value="{{ $centro->id }}"
                                        data-materiales="{{ $centro->materiales->filter(fn ($material) => $material->activo)->pluck('id')->implode(',') }}"
                                        {{ old('fk_id_centro') == $centro->id ? 'selected' : '' }}
                                    >
                                        {{ $centro->nombre }} - {{ $centro->municipio }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Título --}}
                        <div class="form-group full">
                            <label for="titulo">
                                Título de la campaña *
                            </label>

                            <input
                                type="text"
                                id="titulo"
                                name="titulo"
                                value="{{ old('titulo') }}"
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
                            >{{ old('descripcion') }}</textarea>
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
                                value="{{ old('fecha_inicio') }}"
                                min="{{ date('Y-m-d') }}"
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
                                value="{{ old('fecha_fin') }}"
                                min="{{ old('fecha_inicio', date('Y-m-d')) }}"
                                required
                            >
                        </div>

                        {{-- Materiales --}}
                        <div class="form-group full">
                            <h3>Materiales solicitados</h3>

                            <p class="section-description">
                                Selecciona los materiales que necesitas.
                                Solo aparecerán los que acepta el centro elegido.
                            </p>

                            <div
                                id="campania-materiales"
                                class="campania-materiales-grid"
                            >

                                @php
                                    $materialesAnteriores = collect(
                                        old('materiales', [])
                                    );
                                @endphp

                                @foreach($materiales as $material)

                                    @php
                                        $materialAnterior = $materialesAnteriores->first(
                                            fn ($item) =>
                                                (int) ($item['id'] ?? 0) ===
                                                (int) $material->id
                                        );

                                        $seleccionado = $materialAnterior !== null;
                                    @endphp

                                    <div
                                        class="campania-material-card"
                                        data-material-id="{{ $material->id }}"
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
                                            >

                                            <span>{{ $material->nombre }}</span>
                                        </label>

                                        <label for="cantidad_{{ $material->id }}">
                                            Cantidad solicitada
                                            ({{ $material->unidad_medida }})
                                        </label>

                                        <input
                                            type="number"
                                            id="cantidad_{{ $material->id }}"
                                            name="materiales[{{ $loop->index }}][cantidad]"
                                            value="{{ $materialAnterior['cantidad'] ?? '' }}"
                                            min="0.001"
                                            max="999999999.999"
                                            step="0.001"
                                            placeholder="Ejemplo: 25.500"
                                            {{ $seleccionado ? 'required' : 'disabled' }}
                                        >

                                    </div>

                                @endforeach

                            </div>

                            <p
                                id="campania-sin-materiales"
                                class="section-description"
                                style="display:none;"
                            >
                                El centro seleccionado no tiene materiales
                                activos configurados para sus campañas.
                            </p>
                        </div>

                    </div>

                    <p class="section-description">
                        La campaña se guardará inicialmente como borrador.
                    </p>

                    <div class="campania-acciones">

                        <button
                            type="submit"
                            class="btn-primary"
                            id="btn-crear-campania"
                            disabled
                        >
                            Registrar campaña
                        </button>

                        <a
                            href="{{ route('campanias.index') }}"
                            class="btn-secondary"
                        >
                            Cancelar
                        </a>

                    </div>

                </form>

            @endif

        </div>

    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const centroSelect = document.getElementById('fk_id_centro');
    const tarjetas = document.querySelectorAll('.campania-material-card');
    const mensajeSinMateriales = document.getElementById(
        'campania-sin-materiales'
    );
    const botonRegistrar = document.getElementById(
        'btn-crear-campania'
    );

    const fechaInicio = document.getElementById('fecha_inicio');
    const fechaFin = document.getElementById('fecha_fin');

    // Actualizar los materiales según el centro seleccionado.
    function actualizarMateriales() {

        if (!centroSelect) {
            return;
        }

        const opcion = centroSelect.options[
            centroSelect.selectedIndex
        ];

        const aceptados = new Set(
            opcion && opcion.dataset.materiales
                ? opcion.dataset.materiales.split(',').filter(Boolean)
                : []
        );

        let cantidadAceptada = 0;
        let materialSeleccionado = false;

        tarjetas.forEach(function (tarjeta) {

            const id = tarjeta.dataset.materialId;

            const checkbox = tarjeta.querySelector(
                'input[type="checkbox"]'
            );

            const cantidad = tarjeta.querySelector(
                'input[type="number"]'
            );

            const aceptado = centroSelect.value !== '' &&
                aceptados.has(id);

            tarjeta.style.display = aceptado ? 'grid' : 'none';

            if (!aceptado) {

                checkbox.checked = false;
                cantidad.value = '';
                cantidad.disabled = true;
                cantidad.required = false;

            } else {

                cantidadAceptada++;

                cantidad.disabled = !checkbox.checked;
                cantidad.required = checkbox.checked;

                if (checkbox.checked) {
                    materialSeleccionado = true;
                }
            }
        });

        if (mensajeSinMateriales) {
            mensajeSinMateriales.style.display =
                centroSelect.value !== '' && cantidadAceptada === 0
                    ? 'block'
                    : 'none';
        }

        if (botonRegistrar) {
            botonRegistrar.disabled =
                !centroSelect.value ||
                cantidadAceptada === 0 ||
                !materialSeleccionado;
        }
    }

    // Habilitar o deshabilitar la cantidad según la selección.
    tarjetas.forEach(function (tarjeta) {

        const checkbox = tarjeta.querySelector(
            'input[type="checkbox"]'
        );

        const cantidad = tarjeta.querySelector(
            'input[type="number"]'
        );

        checkbox.addEventListener('change', function () {

            cantidad.disabled = !this.checked;
            cantidad.required = this.checked;

            if (!this.checked) {
                cantidad.value = '';
            }

            actualizarMateriales();
        });
    });

    if (centroSelect) {
        centroSelect.addEventListener('change', actualizarMateriales);
        actualizarMateriales();
    }

    // Validar que la fecha final no sea anterior a la inicial.
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
