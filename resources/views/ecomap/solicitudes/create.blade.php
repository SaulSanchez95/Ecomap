
@extends('ecomap.layout')

@section('title', 'Preinscripción de acopio | EcoMap')

@section('content')

<section class="page-banner">
    <div class="container">
        <span class="tag">PORTAL CIUDADANO</span>
        <h1>Registrar preinscripción</h1>
        <p>
            Informa al centro de acopio qué materiales planeas entregar
            y en qué fecha.
        </p>
    </div>
</section>

<section class="section">
    <div class="container">

        @if(session('success'))
            <div class="status">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="status">
                <strong>No fue posible registrar la solicitud:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($centros->isEmpty())

            <div class="form-card solicitud-card">
                <h2>No hay centros disponibles</h2>

                <p>
                    Actualmente no existen centros de acopio validados
                    y activos que puedan recibir solicitudes.
                </p>

                <p>
                    Cuando un centro sea autorizado por un administrador,
                    aparecerá disponible en este apartado.
                </p>

                <a href="{{ route('ciudadano') }}" class="btn-primary">
                    Volver al catálogo
                </a>
            </div>

        @else

            <div class="form-card solicitud-card">

                <div class="solicitud-heading">
                    <h2>Información de tu entrega</h2>
                    <p>
                        Completa los siguientes datos para informar
                        al centro sobre tu próxima entrega.
                    </p>
                </div>

                <form
                    action="{{ route('solicitudes.store') }}"
                    method="POST"
                >
                    @csrf

                    <div class="form-grid solicitud-form-grid">

                        {{-- Centro de acopio --}}
                        <div class="form-group full">
                            <label for="fk_id_centro">
                                Centro de acopio *
                            </label>

                            <select
                                name="fk_id_centro"
                                id="fk_id_centro"
                                required
                            >
                                <option value="">
                                    Selecciona un centro
                                </option>

                                @foreach($centros as $centro)
                                    <option
                                        value="{{ $centro->id }}"
                                        data-materiales="{{ $centro->materiales->pluck('id')->implode(',') }}"
                                        {{ old('fk_id_centro') == $centro->id ? 'selected' : '' }}
                                    >
                                        {{ $centro->nombre }} -
                                        {{ $centro->municipio }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Fecha estimada --}}
                        <div class="form-group">
                            <label for="fecha_entrega_estimada">
                                Fecha estimada de entrega *
                            </label>

                            <input
                                type="date"
                                name="fecha_entrega_estimada"
                                id="fecha_entrega_estimada"
                                min="{{ date('Y-m-d') }}"
                                value="{{ old('fecha_entrega_estimada') }}"
                                required
                            >
                        </div>

                        {{-- Información --}}
                        <div class="solicitud-info-box">
                            <strong>Antes de registrar</strong>
                            <p>
                                Selecciona los materiales que planeas llevar
                                y proporciona una cantidad aproximada.
                            </p>
                            <p>
                                Esta preinscripción es informativa y no
                                representa una recepción confirmada.
                            </p>
                        </div>

                        {{-- Materiales --}}
                        <div class="form-group full solicitud-materiales-section">

                            <div>
                                <h3>Materiales que llevarás</h3>

                                <p class="section-description">
                                    Selecciona los materiales aceptados por
                                    el centro e indica la cantidad estimada.
                                </p>
                            </div>

                            @php
                                $materialesAnteriores = collect(
                                    old('materiales', [])
                                );
                            @endphp

                            <div
                                id="lista-materiales"
                                class="solicitud-materiales-grid"
                            >

                                @foreach($materiales as $material)

                                    @php
                                        $materialAnterior = $materialesAnteriores
                                            ->first(
                                                fn ($item) =>
                                                    (int) ($item['id'] ?? 0) ===
                                                    (int) $material->id
                                            );

                                        $seleccionado = $materialAnterior !== null;
                                    @endphp

                                    <div
                                        class="material-item solicitud-material-card"
                                        data-material-id="{{ $material->id }}"
                                    >

                                        <label
                                            class="material-opcion"
                                            for="material_{{ $material->id }}"
                                        >
                                            <input
                                                type="checkbox"
                                                id="material_{{ $material->id }}"
                                                class="material-checkbox"
                                                name="materiales[{{ $loop->index }}][id]"
                                                value="{{ $material->id }}"
                                                {{ $seleccionado ? 'checked' : '' }}
                                            >

                                            <span>{{ $material->nombre }}</span>
                                        </label>

                                        <label
                                            for="cantidad_{{ $material->id }}"
                                            class="material-cantidad-label"
                                        >
                                            Cantidad estimada
                                            ({{ $material->unidad_medida }})
                                        </label>

                                        <input
                                            type="number"
                                            id="cantidad_{{ $material->id }}"
                                            name="materiales[{{ $loop->index }}][cantidad]"
                                            min="0.001"
                                            max="999999999.999"
                                            step="0.001"
                                            placeholder="Ej. 5.500"
                                            value="{{ $materialAnterior['cantidad'] ?? '' }}"
                                            class="cantidad-material"
                                            {{ $seleccionado ? 'required' : 'disabled' }}
                                        >

                                    </div>

                                @endforeach

                            </div>

                            <p
                                id="sin-materiales"
                                class="section-description"
                                style="display:none;"
                            >
                                Este centro no tiene materiales disponibles
                                para seleccionar.
                            </p>

                        </div>

                        {{-- Comentarios --}}
                        <div class="form-group full">
                            <label for="comentarios">
                                Comentarios adicionales
                            </label>

                            <textarea
                                name="comentarios"
                                id="comentarios"
                                rows="3"
                                maxlength="1000"
                                placeholder="Agrega información adicional sobre tu entrega..."
                            >{{ old('comentarios') }}</textarea>
                        </div>

                    </div>

                    <div class="solicitud-form-actions">

                        <button
                            type="submit"
                            class="btn-primary"
                            id="btn-registrar"
                        >
                            Registrar preinscripción
                        </button>

                        <a
                            href="{{ route('ciudadano') }}"
                            class="btn-secondary"
                        >
                            Cancelar
                        </a>

                    </div>

                </form>

            </div>

        @endif

    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const centroSelect = document.getElementById('fk_id_centro');
    const materiales = document.querySelectorAll('.material-item');
    const mensajeSinMateriales = document.getElementById('sin-materiales');
    const botonRegistrar = document.getElementById('btn-registrar');

    if (!centroSelect) {
        return;
    }

    function actualizarMateriales() {

        const opcion = centroSelect.options[centroSelect.selectedIndex];

        const aceptados = opcion && opcion.dataset.materiales
            ? opcion.dataset.materiales.split(',')
            : [];

        let cantidadAceptada = 0;
        let materialSeleccionado = false;

        materiales.forEach(function (elemento) {

            const id = elemento.dataset.materialId;

            const checkbox = elemento.querySelector(
                '.material-checkbox'
            );

            const cantidad = elemento.querySelector(
                '.cantidad-material'
            );

            const aceptado = aceptados.includes(id);

            elemento.style.display = aceptado ? 'grid' : 'none';

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
                centroSelect.value && cantidadAceptada === 0
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

    document.querySelectorAll('.material-checkbox').forEach(
        function (checkbox) {

            checkbox.addEventListener('change', function () {

                const cantidad = this.closest('.material-item')
                    .querySelector('.cantidad-material');

                cantidad.disabled = !this.checked;
                cantidad.required = this.checked;

                if (!this.checked) {
                    cantidad.value = '';
                }

                actualizarMateriales();
            });
        }
    );

    centroSelect.addEventListener('change', actualizarMateriales);

    actualizarMateriales();
});
</script>

@endsection