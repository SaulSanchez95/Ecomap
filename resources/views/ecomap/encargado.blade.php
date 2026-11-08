@extends('ecomap.layout')

@section('title', 'EcoMap | Panel del encargado')

@section('content')

<section class="page-banner">

    <div class="container">

        <span class="tag">PANEL ADMINISTRATIVO</span>

        <h1>Panel del centro de acopio</h1>

        <p>
            Administra la información, registra materiales
            y consulta la actividad de tu centro.
        </p>

    </div>

</section>


<section class="section">

    <div class="container">

        <div class="dashboard-header">

            <div>
                <h2>Centro de Reciclaje Colima</h2>
                <p>Vista de demostración del encargado.</p>
            </div>

            <span class="status">Centro activo</span>

        </div>


        <div class="stats-grid">

            <div class="stat-card">
                <span>Registros de recepción</span>
                <h2>128</h2>
                <small>Registros de ejemplo</small>
            </div>

            <div class="stat-card">
                <span>Material recuperado</span>
                <h2>850 kg</h2>
                <small>Cantidad de ejemplo</small>
            </div>

            <div class="stat-card">
                <span>Materiales aceptados</span>
                <h2>5</h2>
                <small>Tipos registrados</small>
            </div>

            <div class="stat-card">
                <span>Campañas activas</span>
                <h2>2</h2>
                <small>Campañas de ejemplo</small>
            </div>

        </div>


        <div class="dashboard-grid">

            <div class="form-card">

                <h2>Registrar recepción de material</h2>

                <form>

                    <div class="form-group">
                        <label>Material recibido</label>

                        <select required>
                            <option value="">Seleccionar material</option>
                            <option>Papel y cartón</option>
                            <option>Plástico</option>
                            <option>Vidrio</option>
                            <option>Metales</option>
                            <option>Baterías</option>
                            <option>Electrónicos</option>
                        </select>
                    </div>

                    <br>

                    <div class="form-group">
                        <label>Cantidad recibida (kg)</label>

                        <input
                            type="number"
                            min="0.01"
                            step="0.01"
                            placeholder="Ej. 25.50"
                            required
                        >
                    </div>

                    <br>

                    <div class="form-group">
                        <label>Fecha de recepción</label>

                        <input type="date" required>
                    </div>

                    <br>

                    <button type="button" class="btn-primary">
                        Guardar recepción
                    </button>

                </form>

            </div>


            <div class="form-card">

                <h2>Información del centro</h2>

                <div class="form-group">
                    <label>Nombre del centro</label>

                    <input
                        type="text"
                        value="Centro de Reciclaje Colima"
                    >
                </div>

                <br>

                <div class="form-group">
                    <label>Dirección</label>

                    <input
                        type="text"
                        value="Colima, Colima"
                    >
                </div>

                <br>

                <div class="form-group">
                    <label>Teléfono</label>

                    <input
                        type="text"
                        placeholder="Teléfono del centro"
                    >
                </div>

                <br>

                <div class="form-group">
                    <label>Horario</label>

                    <input
                        type="text"
                        value="Lunes a viernes, 8:00 a 16:00"
                    >
                </div>

                <br>

                <button type="button" class="btn-secondary">
                    Actualizar información
                </button>

            </div>

        </div>


        <div class="form-card">

            <h2>Últimos registros de recepción</h2>

            <div class="table-container">

                <table>

                    <thead>

                        <tr>
                            <th>Material</th>
                            <th>Cantidad</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                        </tr>

                    </thead>

                    <tbody>

                        <tr>
                            <td>Plástico</td>
                            <td>25.50 kg</td>
                            <td>05/11/2026</td>
                            <td><span class="status">Registrado</span></td>
                        </tr>

                        <tr>
                            <td>Cartón</td>
                            <td>40.00 kg</td>
                            <td>04/11/2026</td>
                            <td><span class="status">Registrado</span></td>
                        </tr>

                        <tr>
                            <td>Vidrio</td>
                            <td>18.75 kg</td>
                            <td>03/11/2026</td>
                            <td><span class="status">Registrado</span></td>
                        </tr>

                    </tbody>

                </table>

            </div>

        </div>


        <div class="form-card">

            <h2>Publicar una campaña de reciclaje</h2>

            <form>

                <div class="form-grid">

                    <div class="form-group">
                        <label>Título de la campaña</label>

                        <input
                            type="text"
                            placeholder="Ej. Jornada de recolección de plástico"
                        >
                    </div>

                    <div class="form-group">
                        <label>Material relacionado</label>

                        <select>
                            <option>Seleccionar material</option>
                            <option>Plástico</option>
                            <option>Cartón</option>
                            <option>Vidrio</option>
                            <option>Metales</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Fecha de inicio</label>
                        <input type="date">
                    </div>

                    <div class="form-group">
                        <label>Fecha de finalización</label>
                        <input type="date">
                    </div>

                    <div class="form-group full">
                        <label>Descripción</label>

                        <textarea
                            placeholder="Describe la campaña..."
                        ></textarea>
                    </div>

                </div>

                <br>

                <button type="button" class="btn-primary">
                    Publicar campaña
                </button>

            </form>

        </div>
    </div>
</section>
@endsection

/*Pepe Pollo