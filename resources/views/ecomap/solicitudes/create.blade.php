@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Registrar solicitud de acopio</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('solicitudes.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="fk_id_centro">Centro de acopio</label>

            <select name="fk_id_centro" id="fk_id_centro" class="form-control" required>
                <option value="">Selecciona un centro</option>

                @foreach ($centros as $centro)
                    <option value="{{ $centro->id }}"
                        data-materiales="{{ $centro->materiales->pluck('id')->implode(',') }}"
                        {{ old('fk_id_centro') == $centro->id ? 'selected' : '' }}>
                        {{ $centro->nombre }} - {{ $centro->municipio }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="fecha_entrega_estimada">Fecha estimada de entrega</label>
            <input
                type="date"
                name="fecha_entrega_estimada"
                id="fecha_entrega_estimada"
                class="form-control"
                min="{{ date('Y-m-d') }}"
                value="{{ old('fecha_entrega_estimada') }}"
                required
            >
        </div>

        <div class="mb-3">
            <label>Materiales que llevarás</label>

            <div id="lista-materiales">
                @foreach ($materiales as $material)
                    <div class="form-check mb-2 material-item"
                         data-material-id="{{ $material->id }}">

                        <input
                            type="checkbox"
                            class="form-check-input material-checkbox"
                            id="material_{{ $material->id }}"
                            name="materiales[{{ $loop->index }}][id]"
                            value="{{ $material->id }}"
                            {{ collect(old('materiales', []))->contains(fn ($item) => ($item['id'] ?? null) == $material->id) ? 'checked' : '' }}
                        >

                        <label class="form-check-label" for="material_{{ $material->id }}">
                            {{ $material->nombre }}
                        </label>

                        <input
                            type="number"
                            step="0.001"
                            min="0.001"
                            name="materiales[{{ $loop->index }}][cantidad]"
                            class="form-control mt-1 cantidad-material"
                            placeholder="Cantidad estimada en kg"
                            value="{{ old('materiales.' . $loop->index . '.cantidad') }}"
                            {{ collect(old('materiales', []))->contains(fn ($item) => ($item['id'] ?? null) == $material->id) ? '' : 'disabled' }}
                        >
                    </div>
                @endforeach
            </div>

            <small>Solo puedes seleccionar materiales aceptados por el centro elegido.</small>
        </div>

        <div class="mb-3">
            <label for="comentarios">Comentarios adicionales</label>
            <textarea
                name="comentarios"
                id="comentarios"
                class="form-control"
                rows="3"
                maxlength="1000"
            >{{ old('comentarios') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">
            Registrar solicitud
        </button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const centroSelect = document.getElementById('fk_id_centro');
    const materiales = document.querySelectorAll('.material-item');

    function actualizarMateriales() {
        const opcion = centroSelect.options[centroSelect.selectedIndex];
        const aceptados = opcion && opcion.dataset.materiales
            ? opcion.dataset.materiales.split(',')
            : [];

        materiales.forEach(function (elemento) {
            const id = elemento.dataset.materialId;
            const checkbox = elemento.querySelector('.material-checkbox');
            const cantidad = elemento.querySelector('.cantidad-material');
            const aceptado = aceptados.includes(id);

            elemento.style.display = aceptado ? 'block' : 'none';

            if (!aceptado) {
                checkbox.checked = false;
                cantidad.value = '';
                cantidad.disabled = true;
            } else {
                cantidad.disabled = !checkbox.checked;
            }
        });
    }

    document.querySelectorAll('.material-checkbox').forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            const cantidad = this.closest('.material-item')
                .querySelector('.cantidad-material');

            cantidad.disabled = !this.checked;

            if (!this.checked) {
                cantidad.value = '';
            }
        });
    });

    centroSelect.addEventListener('change', actualizarMateriales);
    actualizarMateriales();
});
</script>
@endsection