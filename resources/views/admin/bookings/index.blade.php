@extends('layouts.admin')

@section('title', 'Gestión de Reservas')

@section('content')
<div class="container-fluid pt-4">
    <!-- Título -->
    <div class="container-fluid">
        <h1 class="text-center fw-bold text-secondary">Gestión de Reservas</h1>
    </div>

    <!-- Botón creación de reservas -->
    <div class="row">
        <div class="col text-start pt-4 pb-2">
            <button type="button" class="btn btn-outline-dark fw-bold" data-bs-toggle="modal" data-bs-target="#addBookingModal">Nueva reserva</button>
        </div>

        <!-- Filtro por tipo de reserva -->
        <div class="col text-end pt-4 pb-2">
            <form method="GET" action="{{ route('admin.bookings.index') }}">
                <label for="id_tipo_reserva">Filtrar:</label>
                <select name="id_tipo_reserva" id="id_tipo_reserva" class="form-select d-inline w-auto">
                    <option value="">Todos</option>
                    <option value="1" {{ request('id_tipo_reserva') == 1 ? 'selected' : '' }}>Aeropuerto - Hotel</option>
                    <option value="2" {{ request('id_tipo_reserva') == 2 ? 'selected' : '' }}>Hotel - Aeropuerto</option>
                </select>
                <button type="submit" class="btn btn-outline-secondary">Filtrar</button>
            </form>
        </div>
    </div>

    <!-- Tabla -->
    <div class="row">
        <div class="col">
            <div class="table-responsive">
                <table class="table table-secondary table-striped table-hover w-100 h-100">
                    <thead>
                        <tr>
                            <th scope="col">Id reserva</th>
                            <th scope="col">Localizador</th>
                            <th scope="col">Hotel</th>
                            <th scope="col">Tipo de Reserva</th>
                            <th scope="col">Email Cliente</th>
                            <th scope="col">Fecha de Reserva</th>
                            <th scope="col">Número de Viajeros</th>
                            <th scope="col">Fecha Llegada</th>
                            <th scope="col">Hora Llegada</th>
                            <th scope="col">Número Vuelo Llegada</th>
                            <th scope="col">Origen Vuelo</th>
                            <th scope="col">Hora Vuelo Salida</th>
                            <th scope="col">Fecha Vuelo Salida</th>
                            <th scope="col"><!--Botones--></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bookings as $booking)
                            <tr>
                                <td>{{ $booking->id_reserva }}</td>
                                <td>{{ $booking->localizador }}</td>
                                <td>{{ $booking->hotel->name ?? 'Desconocido' }}</td>
                                <td>{{ $booking->id_tipo_reserva == 1 ? 'Aeropuerto - Hotel' : 'Hotel - Aeropuerto' }}</td>
                                <td>{{ $booking->email_cliente }}</td>
                                <td>{{ $booking->fecha_reserva }}</td>
                                <td>{{ $booking->num_viajeros }}</td>
                                <td>{{ $booking->fecha_entrada ?? '-' }}</td>
                                <td>{{ $booking->hora_entrada ?? '-' }}</td>
                                <td>{{ $booking->numero_vuelo_entrada ?? '-' }}</td>
                                <td>{{ $booking->origen_vuelo_entrada ?? '-' }}</td>
                                <td>{{ $booking->hora_vuelo_salida ?? '-' }}</td>
                                <td>{{ $booking->fecha_vuelo_salida ?? '-' }}</td>
                                <td>
                                <button
                                    class="btn btn-warning"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editBookingModal"
                                    data-booking='@json($booking)'
                                    onclick="setEditBooking(this)">
                                    Editar
                                </button>


                            <form action="{{ route('admin.bookings.destroy', $booking->id_reserva) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">
                                    Eliminar
                                </button>
                            </form>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No se encontraron reservas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


@include('admin.bookings.partials.create')
@include('admin.bookings.partials.edit')


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
/*
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

    // Mostrar/ocultar según el tipo de reserva
    if (tipoReserva == "1") { // Aeropuerto -> Hotel
        aeropuertoHotelFields.style.display = "block";
        hotelAeropuertoFields.style.display = "none";
    } else if (tipoReserva == "2") { // Hotel -> Aeropuerto
        aeropuertoHotelFields.style.display = "none";
        hotelAeropuertoFields.style.display = "block";
    } else {
        aeropuertoHotelFields.style.display = "none";
        hotelAeropuertoFields.style.display = "none";
    }
}

*/
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

////////////////// VIEJO ////////////////////
/*
function setEditBooking(booking) {
// Configurar los campos comunes en el modal de actualización, verificando que cada campo existe
        if (document.getElementById('editIdReserva')) {
            document.getElementById('editIdReserva').value = booking.id_reserva || '';
        }
        if (document.getElementById('editLocalizador')) {
            document.getElementById('editLocalizador').value = booking.localizador || '';
        }
        if (document.getElementById('editIdTipoReserva')) {
            document.getElementById('editIdTipoReserva').value = booking.id_tipo_reserva || '';
        }
        if (document.getElementById('editEmailCliente')) {
            document.getElementById('editEmailCliente').value = booking.email_cliente || '';
        }
        if (document.getElementById('editNumViajeros')) {
            document.getElementById('editNumViajeros').value = booking.num_viajeros || '';
        }
        if (document.getElementById('editVehiculo')) {
            document.getElementById('editVehiculo').value = booking.id_vehiculo || '';
        }
        if (document.getElementById('editIdDestino')) {
            document.getElementById('editIdDestino').value = booking.id_destino || '';
        }
         // Mostrar los campos específicos según el tipo de reserva
    mostrarCampos('edit');

     // Campos específicos para Aeropuerto - Hotel
     if (booking.id_tipo_reserva == 1 || booking.id_tipo_reserva == 'idayvuelta') {
            if (document.getElementById('editFechaEntrada')) {
                document.getElementById('editFechaEntrada').value = booking.fecha_entrada || '';
            }
            if (document.getElementById('editHoraEntrada')) {
                document.getElementById('editHoraEntrada').value = booking.hora_entrada || '';
            }
            if (document.getElementById('editNumeroVueloEntrada')) {
                document.getElementById('editNumeroVueloEntrada').value = booking.numero_vuelo_entrada || '';
            }
            if (document.getElementById('editIdDestino')) {
                document.getElementById('editIdDestino').value = booking.origen_vuelo_entrada || '';
            }
        }

         // Campos específicos para Hotel - Aeropuerto
         if (booking.id_tipo_reserva == 2 || booking.id_tipo_reserva == 'idayvuelta') {
            if (document.getElementById('editFechaVueloSalida')) {
                document.getElementById('editFechaVueloSalida').value = booking.fecha_vuelo_salida || '';
            }
            if (document.getElementById('editHoraVueloSalida')) {
                document.getElementById('editHoraVueloSalid').value = booking.hora_vuelo_salida || '';
            }
        }

        // Mostrar el modal de actualización
        var modal = new bootstrap.Modal(document.getElementById('editBookingModal'));
        modal.show();
    }
*/
////////NUEVO//////////////////

// Función para abrir el modal de edición y llenar los campos
function setEditBooking(button) {
    const booking = JSON.parse(button.getAttribute('data-booking'));
    console.log("Reserva seleccionada:", booking);

    // Configurar los campos del modal
    document.getElementById('editIdReserva').value = booking.id_reserva || '';
    document.getElementById('editLocalizador').value = booking.localizador || '';
    document.getElementById('editIdTipoReserva').value = booking.id_tipo_reserva || '';
    document.getElementById('editEmailCliente').value = booking.email_cliente || '';
    document.getElementById('editNumViajeros').value = booking.num_viajeros || '';
    document.getElementById('editIdDestino').value = booking.id_destino || '';

    // Mostrar campos específicos
    mostrarCampos('edit');

    if (booking.id_tipo_reserva == 1) {
        document.getElementById('editFechaEntrada').value = booking.fecha_entrada || '';
        document.getElementById('editHoraEntrada').value = booking.hora_entrada || '';
        document.getElementById('editNumeroVueloEntrada').value = booking.numero_vuelo_entrada || '';
        document.getElementById('editOrigenVueloEntrada').value = booking.origen_vuelo_entrada || '';
    } else if (booking.id_tipo_reserva == 2) {
        document.getElementById('editFechaVueloSalida').value = booking.fecha_vuelo_salida || '';
        document.getElementById('editHoraVueloSalida').value = booking.hora_vuelo_salida || '';
    }

    // Mostrar el modal
    const modal = new bootstrap.Modal(document.getElementById('editBookingModal'));
    modal.show();
}



</script>

@endsection
