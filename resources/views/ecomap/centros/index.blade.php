
@extends('ecomap.layout')

@section('title', 'Mis centros de acopio | EcoMap')

@section('content')

<section class="page-banner">
    <div class="container">
        <h1>Mis centros de acopio</h1>
        <p>Consulta y administra los centros de acopio que has registrado.</p>
    </div>
</section>

<section class="section">
    <div class="container">

        @if(session('success'))
            <div class="status">
                {{ session('success') }}
            </div>
        @endif

        <div class="dashboard-header">
            <div>
                <h2>Centros registrados</h2>
                <p>Consulta tus centros y su estado de validación.</p>
            </div>

            <a href="{{ route('centros.create') }}" class="btn-primary">
                Registrar centro
            </a>
        </div>

        <div class="center-grid">
            @forelse($centros as $centro)

                <article class="center-card">
                    <div class="center-icon">♻</div>

                    <h3>{{ $centro->nombre }}</h3>

                    <p>
                        <strong>Ubicación:</strong>
                        {{ $centro->direccion }},
                        {{ $centro->municipio }},
                        {{ $centro->estado }}.
                    </p>

                    <p>
                        <strong>Teléfono:</strong>
                        {{ $centro->telefono ?? 'No registrado' }}
                    </p>

                    <p>
                        <strong>Estado:</strong>
                        <span class="status">
                            {{ ucfirst($centro->estado_validacion) }}
                        </span>
                    </p>

                    <div class="center-tags">
                        @forelse($centro->materiales as $material)
                            <span>{{ $material->nombre }}</span>
                        @empty
                            <span>Sin materiales registrados</span>
                        @endforelse
                    </div>

                    <a
                        href="{{ route('centros.show', $centro->id) }}"
                        class="btn-secondary"
                    >
                        Ver detalles
                    </a>
                </article>

            @empty

                <div class="form-card">
                    <h2>Aún no tienes centros registrados</h2>
                    <p>
                        Registra tu primer centro de acopio para comenzar
                        a formar parte de EcoMap.
                    </p>
                    <br>

                    <a href="{{ route('centros.create') }}" class="btn-primary">
                        Registrar mi primer centro
                    </a>
                </div>

            @endforelse
        </div>

    </div>
</section>

@endsection
