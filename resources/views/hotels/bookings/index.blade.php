@extends('layouts.hotel')

@section('title', 'Gestión de Reservas')

@section('content')
<div class="d-flex flex-column vh-100">
    <!-- Título -->
    <header class="text-secondary text-center p-4 fs-1">
        <h1 class="shadow-sm">Gestión de Reservas</h1>
    </header>

    <!-- Botón creación de reservas -->
    <div class="row align-items-center">
        <div class="col text-start pb-2 px-4">
            <button type="button" class="btn btn-outline-secondary fw-bold" data-bs-toggle="modal" data-bs-target="#addBookingModal"><i class="bi bi-plus-circle"></i> Nueva reserva</button>
        </div>

        <!-- Filtros -->
        <div class="col text-end pb-2 px-4">
            <form method="GET" action="{{ route('hotel.bookings.index') }}" class="d-flex justify-content-end">
                <div class="me-2">
                    <label for="id_tipo_reserva" class="form-label">Reservas: </label>
                    <select name="id_tipo_reserva" id="id_tipo_reserva" class="form-select d-inline w-auto">
                        <option value="">Todos</option>
                        <option value="1" {{ request('id_tipo_reserva') == 1 ? 'selected' : '' }}>Aeropuerto - Hotel</option>
                        <option value="2" {{ request('id_tipo_reserva') == 2 ? 'selected' : '' }}>Hotel - Aeropuerto</option>
                    </select>
                </div>
                <div class="me-2">
                    <label for="year" class="form-label">Año:</label>
                    <select name="year" id="year" class="form-select d-inline w-auto">
                        <option value="">Todos</option>
                        <option value="2024" {{ request('year') == 2024 ? 'selected' : '' }}>2024</option>
                        <option value="2025" {{ request('year') == 2025 ? 'selected' : '' }}>2025</option>
                    </select>
                </div>
                <div class="me-2">
                    <label for="month" class="form-label">Mes:</label>
                    <select name="month" id="month" class="form-select d-inline w-auto">
                        <option value="">Todos</option>
                        <option value="1" {{ request('month') == 1 ? 'selected' : '' }}>Enero</option>
                        <option value="2" {{ request('month') == 2 ? 'selected' : '' }}>Febrero</option>
                        <option value="3" {{ request('month') == 3 ? 'selected' : '' }}>Marzo</option>
                        <option value="4" {{ request('month') == 4 ? 'selected' : '' }}>Abril</option>
                        <option value="5" {{ request('month') == 5 ? 'selected' : '' }}>Mayo</option>
                        <option value="6" {{ request('month') == 6 ? 'selected' : '' }}>Junio</option>
                        <option value="7" {{ request('month') == 7 ? 'selected' : '' }}>Julio</option>
                        <option value="8" {{ request('month') == 8 ? 'selected' : '' }}>Agosto</option>
                        <option value="9" {{ request('month') == 9 ? 'selected' : '' }}>Septiembre</option>
                        <option value="10" {{ request('month') == 10 ? 'selected' : '' }}>Octubre</option>
                        <option value="11" {{ request('month') == 11 ? 'selected' : '' }}>Noviembre</option>
                        <option value="12" {{ request('month') == 12 ? 'selected' : '' }}>Diciembre</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-outline-secondary">Filtrar</button>
            </form>
        </div>
    </div>

    <!-- Tabla -->
    <div class="flex-grow-1 overflow-auto ms-2">
        <div class="table-responsive">
            <table class="table table-light table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th scope="col">Localizador</th>
                        <th scope="col">Email Cliente</th>
                        <th scope="col">Recogida</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Hora</th>
                        <th scope="col">Número</th>
                        <th scope="col">Origen</th>
                        <th scope="col">Pasajeros</th>
                        <th><i class="bi bi-gear-fill"></i></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bookings as $booking)
                        <tr>
                            <td>{{ $booking->localizador }}</td>
                            <td>{{ $booking->email_cliente }}</td>
                            <td>{{ $booking->id_tipo_reserva == 1 ? 'Aeropuerto' : 'Hotel' }}</td>
                            <!-- Mostrar fecha dinámica -->
                            <td>
                                {{ $booking->id_tipo_reserva == 1 ? ($booking->fecha_entrada ?? '-') : ($booking->fecha_vuelo_salida ?? '-') }}
                            </td>
                            <!-- Mostrar hora dinámica -->
                            <td>
                                {{ $booking->id_tipo_reserva == 1 ? ($booking->hora_entrada ?? '-') : ($booking->hora_vuelo_salida ?? '-') }}
                            </td>
                            <td>{{ $booking->numero_vuelo_entrada ?? '-' }}</td>
                            <td>{{ $booking->origen_vuelo_entrada ?? '-' }}</td>
                            <td>{{ $booking->num_viajeros }}</td>
                            <td>
                                <button
                                    class="btn btn-sm btn-outline-warning m-1"
                                    title="Editar reserva"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editBookingModal"
                                    data-booking='@json($booking)'
                                    onclick="setEditBooking(this)">
                                    <i class="bi bi-pencil-square"></i>
                                </button>

                                <form action="{{ route('hotel.bookings.destroy', $booking->id_reserva) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                    class="btn btn-sm btn-outline-danger m-1"
                                    title="Eliminar reserva">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center">No se encontraron reservas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @include('hotels.bookings.partials.create')
    @include('hotels.bookings.partials.edit')
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

    function setEditBooking(button) {
        const booking = JSON.parse(button.getAttribute('data-booking'));
        console.log("Reserva seleccionada:", booking);

        // Actualizar la acción del formulario
        const form = document.getElementById('editBookingForm');
        form.action = `/hotel/bookings/${booking.id_reserva}`; // Actualiza la ruta con el id_reserva correcto

        // Configurar los campos del modal
        document.getElementById('editIdReserva').value = booking.id_reserva || '';
        document.getElementById('editLocalizador').value = booking.localizador || '';
        document.getElementById('editIdTipoReserva').value = booking.id_tipo_reserva || '';
        document.getElementById('editEmailCliente').value = booking.email_cliente || '';
        document.getElementById('editNumViajeros').value = booking.num_viajeros || '';
        document.getElementById('editIdDestino').value = booking.id_destino || '';
        document.getElementById('editIdVehiculo').value = booking.id_vehiculo || '';


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
