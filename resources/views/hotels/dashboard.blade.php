@extends('layouts.hotel')

@section('title', 'Panel de Hotel')

@section('content')

<div class="container my-4">
    <div class="row">
        <!-- Gráfico -->
        <div class="col-xl-4 mb-4">
            <div class="bg-white border rounded-2 p-3 shadow" style="height: 100%; max-height: 100vh;">
                <h2 class="fs-5">Comparación de Comisiones</h2>
                <div style="height: 400px; max-height: 100%; overflow: hidden;">
                    {!! $chart->container() !!}
                </div>
                <div>
                    <!-- Tabla de Comisiones -->
                    <h2 class="fs-5">Último trimestre</h2>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Año</th>
                                <th>Mes</th>
                                <th>Comisión Total (€)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($comisiones as $comision)
                                <tr>
                                    <td>{{ $comision->year }}</td>
                                    <td>{{ $comision->month }}</td>
                                    <td>{{ number_format($comision->total_comision, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">No hay comisiones disponibles</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-8 mb-4">
            <!-- Tabla de Reservas -->
            <div class="">
                <div class="col-xl-12">
                    <div class="bg-white border rounded-2 p-3 shadow">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h2 class="mb-0 fs-5">Últimas reservas realizadas</h2>
                            <div>
                                <button type="button" class="btn btn-outline-secondary fw-bold" data-bs-toggle="modal" data-bs-target="#addBookingModal">
                                    <i class="bi bi-plus-circle"></i> Nueva reserva
                                </button>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Localizador</th>
                                    <th>Tipo de Reserva</th>
                                    <th>Email del Cliente</th>
                                    <th>Fecha de Reserva</th>
                                    <th>Fecha de Entrada</th>
                                    <th>Hora de Entrada</th>
                                    <th>Fecha de Vuelo de Salida</th>
                                    <th>Hora de Vuelo de Salida</th>
                                    <th>Número de Viajeros</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($bookings as $booking)
                                    <tr>
                                        <td>{{ $booking->localizador }}</td>
                                        <td>{{ $booking->id_tipo_reserva == 1 ? 'Aeropuerto-Hotel' : 'Hotel-Aeropuerto' }}</td>
                                        <td>{{ $booking->email_cliente }}</td>
                                        <td>{{ $booking->fecha_reserva }}</td>
                                        <td>{{ $booking->fecha_entrada }}</td>
                                        <td>{{ $booking->hora_entrada }}</td>
                                        <td>{{ $booking->fecha_vuelo_salida }}</td>
                                        <td>{{ $booking->hora_vuelo_salida }}</td>
                                        <td>{{ $booking->num_viajeros }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No hay reservas disponibles</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('hotels.partials.create')
<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
{{ $chart->script() }}
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Configuración del formulario de creación de reservas
    const addBookingForm = document.querySelector('#addBookingModal form');
    const createBookingButton = document.getElementById('createBookingButton');
    const loadingSpinner = document.getElementById('loadingSpinner');

    // Mostrar spinner y desactivar el botón al enviar el formulario
    addBookingForm.addEventListener('submit', function () {
        loadingSpinner.style.display = 'block';
        createBookingButton.disabled = true;
    });

    // Mostrar campos según el tipo de reserva en el formulario de creación
    document.getElementById("addIdTipoReserva").addEventListener('change', function () {
        mostrarCampos('add');
    });

    // Inicializar el modal de creación con valores predeterminados
    document.getElementById("addBookingModal").addEventListener('shown.bs.modal', function () {
        document.getElementById("addIdTipoReserva").value = "idayvuelta";
        mostrarCampos('add');
    });
});

// Función para mostrar u ocultar los campos específicos de cada tipo de reserva
function mostrarCampos(modalType) {
    let tipoReserva, aeropuertoHotelFields, hotelAeropuertoFields;

    if (modalType === 'add') {
        tipoReserva = document.getElementById('addIdTipoReserva').value;
        aeropuertoHotelFields = document.getElementById('aeropuerto-hotel-fields-add');
        hotelAeropuertoFields = document.getElementById('hotel-aeropuerto-fields-add');
    } else if (modalType === 'edit') {
        tipoReserva = document.getElementById('editIdTipoReserva').value;
        aeropuertoHotelFields = document.getElementById('aeropuerto-hotel-fields-edit');
        hotelAeropuertoFields = document.getElementById('hotel-aeropuerto-fields-edit');
    }

    if (aeropuertoHotelFields && hotelAeropuertoFields) {
        aeropuertoHotelFields.style.display = (tipoReserva === "1" || tipoReserva === "idayvuelta") ? "block" : "none";
        hotelAeropuertoFields.style.display = (tipoReserva === "2" || tipoReserva === "idayvuelta") ? "block" : "none";
    }
}
</script>
@endsection

