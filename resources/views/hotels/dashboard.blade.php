@extends('layouts.hotel')

@section('title', 'Panel de Hotel')

@section('content')
<div class="container">
    <div class="col text-start pb-2 px-4">
        <button type="button" class="btn btn-outline-secondary fw-bold" data-bs-toggle="modal" data-bs-target="#addBookingModal"><i class="bi bi-plus-circle"></i> Nueva reserva</button>
    </div>
    <div class="table-responsive">
        <table class="table table-light table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Localizador</th>
                    <th scope="col">Recogida</th>
                    <th scope="col">Hotel</th>
                    <th scope="col">Email Cliente</th>
                    <th scope="col">Pasajeros</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Hora</th>
                    <th scope="col">Número</th>
                    <th scope="col">Origen</th>
                    <th scope="col">Vehículo</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($bookings as $booking)
                    <tr>
                        <td>{{ $booking->id_reserva }}</td>
                        <td>{{ $booking->localizador }}</td>
                        <td>{{ $booking->id_tipo_reserva == 1 ? 'Aeropuerto' : 'Hotel' }}</td>
                        <td>{{ $booking->hotel->nombre ?? 'Hotel desconocido' }}</td>
                        <td>{{ $booking->email_cliente }}</td>
                        <td>{{ $booking->num_viajeros }}</td>
                        <td>{{ $booking->id_tipo_reserva == 1 ? $booking->fecha_entrada : $booking->fecha_vuelo_salida }}</td>
                        <td>{{ $booking->id_tipo_reserva == 1 ? $booking->hora_entrada : $booking->hora_vuelo_salida }}</td>
                        <td>{{ $booking->numero_vuelo_entrada ?? '-' }}</td>
                        <td>{{ $booking->origen_vuelo_entrada ?? '-' }}</td>
                        <td>{{ $booking->id_vehiculo ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center">No se encontraron reservas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>


</div>
<div class="container my-4">
    <h1>Bienvenido, {{ $hotel->usuario }}</h1>
    <h2>Comisiones Mensuales</h2>

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
                    <td>{{ \Carbon\Carbon::create()->month($comision->month)->format('F') }}</td>
                    <td>{{ number_format($comision->total_comision, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">No hay datos de comisiones disponibles.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="container mt-5">
    <h1>Gráfico de {{ $hotel->id_hotel }}</h1>
    <div id="chart"></div>
</div>
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

