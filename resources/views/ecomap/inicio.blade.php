@extends('ecomap.layout')

@section('title', 'EcoMap | Inicio')

@section('content')

<section class="hero">
    <div class="hero-content">
        <span class="tag">RECICLAJE EN COLIMA</span>
        <h1>
            Dale una segunda vida
            <span>a tus residuos.</span>
        </h1>
        <p>
            Encuentra centros de acopio, conoce qué materiales reciben
            y participa en campañas de reciclaje desde un solo lugar.
        </p>

        <div class="hero-buttons">
            <a href="{{ route('ciudadano') }}" class="btn-primary">
                Buscar centros
            </a>
            <a href="{{ route('encargado') }}" class="btn-outline">
                Soy encargado
            </a>
        </div>
    </div>
    <div class="hero-image">
        <div class="eco-circle">
            ♻
        </div>
        <h3>Un Colima más limpio comienza contigo.</h3>
    </div>
</section>


<section class="section">
    <div class="container">
        <div class="section-heading">
            <span class="tag">¿QUÉ PUEDES RECICLAR?</span>
            <h2>Conoce los materiales</h2>
            <p>
                Identifica los residuos que pueden recuperarse
                y encuentra dónde entregarlos.
            </p>
        </div>


        <div class="material-grid">
            <div class="material-card">
                <span>📦</span>
                <h3>Papel y cartón</h3>
                <p>Cajas, hojas y periódicos.</p>
            </div>
            <div class="material-card">
                <span>🧴</span>
                <h3>Plástico</h3>
                <p>Botellas y envases.</p>
            </div>
            <div class="material-card">
                <span>🍾</span>
                <h3>Vidrio</h3>
                <p>Frascos y botellas.</p>
            </div>
            <div class="material-card">
                <span>🥫</span>
                <h3>Metales</h3>
                <p>Latas y otros metales.</p>
            </div>
            <div class="material-card">
                <span>🔋</span>
                <h3>Baterías</h3>
                <p>Pilas y acumuladores.</p>
            </div>
            <div class="material-card">
                <span>💻</span>
                <h3>Electrónicos</h3>
                <p>Equipos y dispositivos.</p>
            </div>
        </div>
    </div>
</section>


<section class="section section-green">
    <div class="container">
        <div class="section-heading">
            <span class="tag">¿CÓMO FUNCIONA?</span>
            <h2>Reciclar es más sencillo con EcoMap</h2>
        </div>

        <div class="steps-grid">
            <div class="step-card">
                <span class="step-number">01</span>
                <h3>Busca</h3>
                <p>Encuentra centros de acopio disponibles en Colima.</p>
            </div>
            <div class="step-card">
                <span class="step-number">02</span>
                <h3>Consulta</h3>
                <p>Revisa qué materiales recibe cada centro.</p>
            </div>
            <div class="step-card">
                <span class="step-number">03</span>
                <h3>Entrega</h3>
                <p>Lleva tus residuos al centro seleccionado.</p>
            </div>
            <div class="step-card">
                <span class="step-number">04</span>
                <h3>Contribuye</h3>
                <p>Ayuda a recuperar materiales y reducir residuos.</p>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container callout">
        <div>
            <span class="tag">CENTROS DE ACOPIO</span>
            <h2>¿Administras un centro de reciclaje?</h2>
            <p>
                Forma parte de EcoMap y facilita que más personas
                conozcan los materiales que recibes.
            </p>
        </div>
        <a href="{{ route('encargado') }}" class="btn-primary">
            Entrar como encargado
        </a>
    </div>
</section>
@endsection
/*Pepe Pollo