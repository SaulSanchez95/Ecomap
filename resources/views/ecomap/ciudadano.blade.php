
@extends('ecomap.layout')

@section('title', 'EcoMap | Portal ciudadano')

@section('content')

<section class="page-banner">
    <div class="container">
        <span class="tag">PORTAL CIUDADANO</span>
        <h1>Encuentra dónde reciclar</h1>
        <p>
            Consulta centros de acopio disponibles y descubre
            qué materiales reciben.
        </p>
    </div>
</section>

<section class="section">
    <div class="container">

        <h2>Centros de acopio</h2>

        <p class="section-description">
            Busca por nombre, ubicación o material aceptado.
        </p>

        {{-- Buscador de centros --}}
        <form
            action="{{ route('ciudadano') }}"
            method="GET"
            class="search-box"
        >
            <input
                type="text"
                name="buscar"
                placeholder="Nombre, municipio o ubicación..."
                value="{{ $buscar }}"
                maxlength="120"
            >

            <select name="material">
                <option value="">Todos los materiales</option>

                @foreach($materiales as $material)
                    <option
                        value="{{ $material->id }}"
                        {{ (string) $materialId === (string) $material->id ? 'selected' : '' }}
                    >
                        {{ $material->nombre }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="btn-primary">
                Buscar
            </button>

            @if($buscar !== '' || $materialId)
                <a
                    href="{{ route('ciudadano') }}"
                    class="btn-secondary"
                >
                    Limpiar búsqueda
                </a>
            @endif
        </form>

        {{-- Resultados --}}
        <div class="center-grid">

            @forelse($centros as $centro)

                <article class="center-card">

                    <div class="center-icon">♻</div>

                    <span class="status">Activo</span>

                    <h3>{{ $centro->nombre }}</h3>

                    <p>
                        <strong>Dirección:</strong>
                        {{ $centro->direccion }}
                    </p>

                    <p>
                        <strong>Municipio:</strong>
                        {{ $centro->municipio }},
                        {{ $centro->estado }}
                    </p>

                    <p>
                        <strong>Horario:</strong>
                        {{ $centro->horario ?? 'No especificado' }}
                    </p>

                    <div class="center-tags">
                        @forelse($centro->materiales as $material)
                            <span>{{ $material->nombre }}</span>
                        @empty
                            <span>Sin materiales registrados</span>
                        @endforelse
                    </div>

                    <a
                        href="{{ route('centros.publicos.show', $centro->id) }}"
                        class="btn-secondary"
                    >
                        Ver información
                    </a>

                </article>

            @empty

                <div class="form-card">
                    <h3>No encontramos centros de acopio</h3>

                    <p>
                        No hay centros que coincidan con tu búsqueda
                        o todavía no existen centros validados y activos.
                    </p>

                    <a
                        href="{{ route('ciudadano') }}"
                        class="btn-secondary"
                    >
                        Ver todos los centros
                    </a>
                </div>

            @endforelse

        </div>

    </div>
</section>

{{-- Preinscripción --}}
<section class="section section-green">
    <div class="container">

        <h2>¿Planeas llevar materiales a reciclar?</h2>

        <p class="section-description">
            Registra una preinscripción para informar al centro
            qué materiales planeas entregar y en qué fecha.
        </p>

        <a
            href="{{ route('solicitudes.create') }}"
            class="btn-primary"
        >
            Registrar preinscripción
        </a>

    </div>
</section>

@endsection
