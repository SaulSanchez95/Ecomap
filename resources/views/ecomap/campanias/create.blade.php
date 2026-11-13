
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

            @if($centros->isEmpty())
                <div class="status">
                    No tienes centros activos y validados para crear campañas.
                </div>

                <a href="{{ route('centros.index') }}" class="btn-secondary">
                    Volver a mis centros
                </a>
            @else

                <form action="{{ route('campanias.store') }}" method="POST">
                    @csrf

                    <div class="form-grid">

                        <div class="form-group full">
                            <label for="fk_id_centro">Centro de acopio *</label>

                            <select id="fk_id_centro" name="fk_id_centro" required>
                                <option value="">Selecciona un centro</option>

                                @foreach($centros as $centro)
                                    <option value="{{ $centro->id }}"
                                        {{ old('fk_id_centro') == $centro->id ? 'selected' : '' }}>
                                        {{ $centro->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group full">
                            <label for="titulo">Título de la campaña *</label>

                            <input
                                type="text"
                                id="titulo"
                                name="titulo"
                                value="{{ old('titulo') }}"
                                maxlength="120"
                                required
                            >
                        </div>

                        <div class="form-group full">
                            <label for="descripcion">Descripción</label>

                            <textarea
                                id="descripcion"
                                name="descripcion"
                            >{{ old('descripcion') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="fecha_inicio">Fecha de inicio *</label>

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
                            <label for="fecha_fin">Fecha de finalización *</label>

                            <input
                                type="date"
                                id="fecha_fin"
                                name="fecha_fin"
                                value="{{ old('fecha_fin') }}"
                                required
                            >
                        </div>

                        <div class="form-group full">
                            <h3>Materiales solicitados</h3>

                            <p class="section-description">
                                Selecciona los materiales que necesitas e indica
                                la cantidad solicitada de cada uno.
                            </p>
                        </div>

                        @foreach($materiales as $material)

                            <div class="form-group">

                                <label for="material_{{ $material->id }}">
                                    <input
                                        type="checkbox"
                                        id="material_{{ $material->id }}"
                                        name="materiales[{{ $loop->index }}][id]"
                                        value="{{ $material->id }}"
                                        {{ old('materiales.'.$loop->index.'.id') == $material->id ? 'checked' : '' }}
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
                                </label>

                                <label for="cantidad_{{ $material->id }}">
                                    Cantidad solicitada ({{ $material->unidad_medida }})
                                </label>

                                <input
                                    type="number"
                                    id="cantidad_{{ $material->id }}"
                                    name="materiales[{{ $loop->index }}][cantidad]"
                                    value="{{ old('materiales.'.$loop->index.'.cantidad') }}"
                                    min="0.001"
                                    max="999999999.999"
                                    step="0.001"
                                    {{ old('materiales.'.$loop->index.'.id') == $material->id ? 'required' : 'disabled' }}
                                >

                            </div>

                        @endforeach

                    </div>

                    <br>

                    <p class="section-description">
                        La campaña se guardará inicialmente como borrador.
                    </p>

                    <br>

                    <button type="submit" class="btn-primary">
                        Registrar campaña
                    </button>

                    <a href="{{ route('campanias.index') }}" class="btn-secondary">
                        Cancelar
                    </a>

                </form>

            @endif

        </div>

    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const fechaInicio = document.getElementById('fecha_inicio');
    const fechaFin = document.getElementById('fecha_fin');

    if (!fechaInicio || !fechaFin) {
        return;
    }

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
