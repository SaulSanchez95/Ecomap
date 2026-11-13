@extends('ecomap.layout')

@section('title', 'Detalles de campaña | EcoMap')

@section('content')

<section class="page-banner">
    <div class="container">
        <h1>{{ $campania->titulo }}</h1>
        <p>Información detallada de la campaña.</p>
    </div>
</section>

<section class="section">
    <div class="container">

        @if(session('success'))
            <div class="status">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="status">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="status">
                <strong>Se encontraron errores:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-card">

            <h2>Información general</h2>

            <p>
                <strong>Centro de acopio:</strong>
                {{ $campania->centro->nombre }}
            </p>

            <p>
                <strong>Descripción:</strong>
                {{ $campania->descripcion ?? 'Sin descripción registrada' }}
            </p>

            <p>
                <strong>Fecha de inicio:</strong>
                {{ $campania->fecha_inicio?->format('d/m/Y') ?? 'No especificada' }}
            </p>

            <p>
                <strong>Fecha de finalización:</strong>
                {{ $campania->fecha_fin?->format('d/m/Y') ?? 'No especificada' }}
            </p>

            <p>
                <strong>Estado:</strong>
                <span class="status">
                    {{ ucfirst($campania->estado) }}
                </span>
            </p>

            <hr>

            <h3>Materiales solicitados</h3>

            <div class="center-tags">
                @forelse($campania->materiales as $material)
                    <span>
                        {{ $material->nombre }}:
                        {{ number_format((float) $material->pivot->cantidad_solicitada, 3) }}
                        {{ $material->unidad_medida }}
                    </span>
                @empty
                    <span>Sin materiales registrados</span>
                @endforelse
            </div>

            <br>

            @if($campania->estado === 'borrador')

                <a href="{{ route('campanias.edit', $campania->id) }}"
                   class="btn-primary">
                    Editar campaña
                </a>

                <form
                    action="{{ route('campanias.destroy', $campania->id) }}"
                    method="POST"
                    onsubmit="return confirm('¿Estás seguro de eliminar esta campaña? Esta acción no se puede deshacer.');"
                    style="display: inline;"
                >
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn-secondary">
                        Eliminar campaña
                    </button>
                </form>

            @endif

            <a href="{{ route('campanias.index') }}"
               class="btn-secondary">
                Volver a mis campañas
            </a>

        </div>

    </div>
</section>

@endsection