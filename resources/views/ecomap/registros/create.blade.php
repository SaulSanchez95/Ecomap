
@extends('ecomap.layout')

@section('title', 'Registrar recepción de material | EcoMap')

@section('content')

<section class="page-banner">
    <div class="container">
        <h1>Registrar recepción de material</h1>
        <p>Registra las cantidades reales de materiales recibidos en tus centros de acopio.</p>
    </div>
</section>

<section class="section">
    <div class="container">

        <div class="form-card">

            @if (session('success'))
                <div class="status">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="status">
                    <strong>No fue posible guardar el registro:</strong>

                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($centros->isEmpty())

                <p>No tienes centros de acopio validados y activos para registrar recepciones.</p>

                <a href="{{ route('registros.index') }}" class="btn-secondary">
                    Volver al historial
                </a>

            @else

                <form action="{{ route('registros.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="fk_id_centro">Centro de acopio</label>

                        <select
                            name="fk_id_centro"
                            id="fk_id_centro"
                            required
                        >
                            <option value="">Selecciona un centro</option>

                            @foreach ($centros as $centro)
                                <option
                                    value="{{ $centro->id }}"
                                    data-materiales="{{ $centro->materiales->pluck('id')->implode(',') }}"
                                    {{ old('fk_id_centro') == $centro->id ? 'selected' : '' }}
                                >
                                    {{ $centro->nombre }} - {{ $centro->municipio }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="fk_id_material">Material recibido</label>

                        <select
                            name="fk_id_material"
                            id="fk_id_material"
                            required
                            disabled
                        >
                            <option value="">Primero selecciona un centro</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="cantidad_recibida">Cantidad recibida (kg)</label>

                        <input
                            type="number"
                            name="cantidad_recibida"
                            id="cantidad_recibida"
                            step="0.001"
                            min="0.001"
                            value="{{ old('cantidad_recibida') }}"
                            placeholder="Ejemplo: 25.500"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="fecha">Fecha de recepción</label>

                        <input
                            type="date"
                            name="fecha"
                            id="fecha"
                            max="{{ date('Y-m-d') }}"
                            value="{{ old('fecha', date('Y-m-d')) }}"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="observaciones">Observaciones</label>

                        <textarea
                            name="observaciones"
                            id="observaciones"
                            rows="3"
                            maxlength="1000"
                            placeholder="Agrega información adicional si es necesario."
                        >{{ old('observaciones') }}</textarea>
                    </div>

                    <br>

                    <button type="submit" class="btn-primary">
                        Guardar registro
                    </button>

                    <a href="{{ route('registros.index') }}" class="btn-secondary">
                        Cancelar
                    </a>
                </form>

            @endif

        </div>

    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const centroSelect = document.getElementById('fk_id_centro');
    const materialSelect = document.getElementById('fk_id_material');

    if (!centroSelect || !materialSelect) {
        return;
    }

    const materiales = @json(
        \App\Models\Material::where('activo', true)
            ->orderBy('nombre')
            ->get(['id', 'nombre'])
    );

    const materialAnterior = @json(old('fk_id_material'));

    function actualizarMateriales() {
        const opcion = centroSelect.options[centroSelect.selectedIndex];

        const aceptados = opcion && opcion.dataset.materiales
            ? opcion.dataset.materiales.split(',')
            : [];

        materialSelect.innerHTML = '';

        const opcionInicial = document.createElement('option');
        opcionInicial.value = '';
        opcionInicial.textContent = 'Selecciona un material';

        materialSelect.appendChild(opcionInicial);

        materiales.forEach(function (material) {
            if (aceptados.includes(String(material.id))) {
                const opcionMaterial = document.createElement('option');

                opcionMaterial.value = material.id;
                opcionMaterial.textContent = material.nombre;

                if (String(materialAnterior) === String(material.id)) {
                    opcionMaterial.selected = true;
                }

                materialSelect.appendChild(opcionMaterial);
            }
        });

        materialSelect.disabled = aceptados.length === 0;

        if (aceptados.length === 0) {
            opcionInicial.textContent =
                'Este centro no tiene materiales configurados';
        }
    }

    centroSelect.addEventListener('change', actualizarMateriales);

    actualizarMateriales();
});
</script>

@endsection