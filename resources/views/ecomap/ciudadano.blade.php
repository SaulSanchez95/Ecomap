@extends('ecomap.layout')

@section('title', 'EcoMap | Ciudadano')

@section('content')

<section class="page-banner">
    <div class="container">
        <span class="tag">PORTAL CIUDADANO</span>
        <h1>Encuentra dónde reciclar</h1>
        <p>
            Consulta centros de acopio y descubre qué materiales reciben.
        </p>
    </div>
</section>


<section class="section">
    <div class="container">
        <h2>Centros de acopio</h2>
        <p class="section-description">
            Busca un centro según su nombre o el material que recibe.
        </p>


        <div class="search-box">
            <input
                type="text"
                placeholder="Buscar centro de acopio..."
            >
            <select>
                <option value="">Todos los materiales</option>
                <option>Papel y cartón</option>
                <option>Plástico</option>
                <option>Vidrio</option>
                <option>Metales</option>
                <option>Baterías</option>
                <option>Electrónicos</option>
            </select>
            <button class="btn-primary" type="button">
                Buscar
            </button>
        </div>


        <div class="center-grid">
            <article class="center-card">
                <div class="center-icon">♻</div>
                <span class="status">Activo</span>
                <h3>Centro de Reciclaje Colima</h3>
                <p>
                    <strong>Dirección:</strong>
                    Colima, Colima.
                </p>
                <p>
                    <strong>Horario:</strong>
                    Lunes a viernes, 8:00 a 16:00.
                </p>
                <div class="center-tags">
                    <span>Plástico</span>
                    <span>Cartón</span>
                    <span>Vidrio</span>
                </div>
                <button class="btn-secondary" type="button">
                    Ver información
                </button>
            </article>

            <article class="center-card">
                <div class="center-icon">♻</div>
                <span class="status">Activo</span>
                <h3>Centro de Acopio Villa de Álvarez</h3>
                <p>
                    <strong>Dirección:</strong>
                    Villa de Álvarez, Colima.
                </p>
                <p>
                    <strong>Horario:</strong>
                    Lunes a sábado, 9:00 a 15:00.
                </p>
                <div class="center-tags">
                    <span>Metales</span>
                    <span>Plástico</span>
                    <span>Cartón</span>
                </div>
                <button class="btn-secondary" type="button">
                    Ver información
                </button>
            </article>

            <article class="center-card">
                <div class="center-icon">♻</div>
                <span class="status">Activo</span>
                <h3>Centro de Recuperación Ambiental</h3>
                <p>
                    <strong>Dirección:</strong>
                    Comala, Colima.
                </p>
                <p>
                    <strong>Horario:</strong>
                    Lunes a viernes, 9:00 a 17:00.
                </p>
                <div class="center-tags">
                    <span>Papel</span>
                    <span>Vidrio</span>
                    <span>Electrónicos</span>
                </div>
                <button class="btn-secondary" type="button">
                    Ver información
                </button>
            </article>
        </div>
    </div>
</section>


<section class="section section-green">
    <div class="container">
        <h2>Preinscribir una entrega</h2>
        <p class="section-description">
            Indica qué materiales planeas llevar al centro de acopio.
        </p>
        <div class="form-card">

            <form>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Centro de acopio</label>
                        <select required>
                            <option value="">Selecciona un centro</option>
                            <option>Centro de Reciclaje Colima</option>
                            <option>Centro de Acopio Villa de Álvarez</option>
                            <option>Centro de Recuperación Ambiental</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Material</label>
                        <select required>
                            <option value="">Selecciona un material</option>
                            <option>Papel y cartón</option>
                            <option>Plástico</option>
                            <option>Vidrio</option>
                            <option>Metales</option>
                            <option>Baterías</option>
                            <option>Electrónicos</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Cantidad estimada (kg)</label>
                        <input
                            type="number"
                            min="0.01"
                            step="0.01"
                            placeholder="Ej. 5.50"
                            required
                        >
                    </div>
                    <div class="form-group">
                        <label>Fecha estimada</label>
                        <input type="date" required>
                    </div>
                </div>
                <br>
                <button type="button" class="btn-primary">
                    Registrar preinscripción
                </button>
            </form>
        </div>
    </div>
</section>

@endsection
/*Pepe Pollo