
@extends('ecomap.layout')

@section('title', 'Mis campañas | EcoMap')

@section('content')

<section class="page-banner">
    <div class="container">
        <h1>Mis campañas</h1>
        <p>Administra las campañas de reciclaje de tus centros de acopio.</p>
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

        <div class="dashboard-header">
            <div>
                <h2>Campañas registradas</h2>
                <p>Consulta y administra tus campañas.</p>
            </div>

            <a href="{{ route('campanias.create') }}" class="btn-primary">
                Crear campaña
            </a>
        </div>

        <div class="center-grid">

            @forelse($campanias as $campania)

                <article class="center-card">

                    <div class="center-icon">♻</div>

                    <h3>{{ $campania->titulo }}</h3>

                    <p>
                        <strong>Centro:</strong>
                        {{ $campania->centro->nombre }}
                    </p>

                    <p>
                        <strong>Descripción:</strong>
                        {{ $campania->descripcion ?? 'Sin descripción' }}
                    </p>

                    <p>
                        <strong>Inicio:</strong>
                        {{ $campania->fecha_inicio?->format('d/m/Y') ?? 'No especificada' }}
                    </p>

                    <p>
                        <strong>Finalización:</strong>
                        {{ $campania->fecha_fin?->format('d/m/Y') ?? 'No especificada' }}
                    </p>

                    <p>
                        <strong>Estado:</strong>
                        <span class="status">
                            {{ ucfirst($campania->estado) }}
                        </span>
                    </p>

                    <h4>Materiales solicitados</h4>

                    <div class="center-tags">
                        @forelse($campania->materiales as $material)
                            <span>
                                {{ $material->nombre }}:
                                {{ $material->pivot->cantidad_solicitada }}
                                {{ $material->unidad_medida }}
                            </span>
                        @empty
                            <span>Sin materiales registrados</span>
                        @endforelse
                    </div>

                    <br>

                    <a href="{{ route('campanias.show', $campania->id) }}"
                       class="btn-secondary">
                        Ver detalles
                    </a>

                    @if($campania->estado === 'borrador')

                        <a href="{{ route('campanias.edit', $campania->id) }}"
                           class="btn-primary">
                            Editar
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
                                Eliminar
                            </button>
                        </form>

                    @endif

                </article>

            @empty

                <div class="form-card">
                    <h2>Aún no tienes campañas</h2>

                    <p>
                        Crea una campaña para indicar qué materiales
                        necesita tu centro de acopio.
                    </p>

                    <br>

                    <a href="{{ route('campanias.create') }}" class="btn-primary">
                        Crear mi primera campaña
                    </a>
                </div>

            @endforelse

        </div>

    </div>
</section>

@endsection
