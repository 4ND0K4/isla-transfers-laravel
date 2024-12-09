@extends('layouts.traveler')

@section('content')

    <!-- //////////////////////////////////////////////// BLOQUE PRINCIPAL //////////////////////////////////////////////// -->
    <div class="container">
        <div class="row">
            <div class="col-3 bg-light border rounded-2 py-3 me-1 my-3">
                <!-- Título -->
                <h1 class="text-center pt-3 fw-light text-success fs-4">¡Hola, {{ htmlspecialchars($_SESSION['travelerName'] ?? $traveler->nombre) }}!</h1>
                <!-- Subtítulo -->
                <h2 class="text-center text-secondary fw-bold pt-3 fs-6">Gestiona tus transfers.</h2>
                <!-- Botón de crear reserva -->
                <div class="col text-center fw-bold py-3">
                    <button type="button" class="btn btn-lg text-warning" data-bs-toggle="modal" data-bs-target="#addBookingModal">
                        <i class="bi bi-journal-plus display-5"></i>
                    </button>
                </div>
            </div>
            <!-- Calendario -->
            <div class="col-8 bg-white border rounded-2 py-3 me-1 my-3" id="calendar"></div>
    </div>

<!-- Incluir los modales -->
@include('travelers.partials.create')
@include('travelers.partials.edit')
@include('travelers.partials.profile')

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const addBookingForm = document.querySelector('#addBookingModal form');
        const createBookingButton = document.getElementById('createBookingButton');
        const loadingSpinner = document.getElementById('loadingSpinner');

        addBookingForm.addEventListener('submit', function () {
            loadingSpinner.style.display = 'block';
            createBookingButton.disabled = true;
        });

        document.getElementById("addIdTipoReserva").addEventListener('change', function () {
            mostrarCampos('add');
        });

        document.getElementById("addBookingModal").addEventListener('shown.bs.modal', function () {
            document.getElementById("addIdTipoReserva").value = "idayvuelta";
            mostrarCampos('add');
        });

        document.getElementById("editIdTipoReserva").addEventListener('change', function () {
            mostrarCampos('edit');
        });

        document.getElementById("editBookingModal").addEventListener('shown.bs.modal', function () {
            mostrarCampos('edit');
        });

        const calendarEl = document.getElementById('calendar');
    });

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

    /* Función para actualizar la reserva según su id_tipo_reserva */
    function editBookingForm(idReserva) {
        // Llamada AJAX para obtener los datos de la reserva desde el servidor
        fetch(`{{ url('bookings/getCalendarEvents') }}?id_reserva=${idReserva}`)
            .then(response => response.json())
            .then(booking => {
                if (booking.error) {
                    console.error('Error al obtener los datos de la reserva:', booking.error);
                    return;
                }

                // Configurar los campos comunes en el modal de actualización
                document.getElementById('editIdReserva').value = booking.id_reserva || '';
                document.getElementById('editLocalizador').value = booking.localizador || '';
                document.getElementById('editIdTipoReserva').value = booking.id_tipo_reserva || '';
                document.getElementById('editEmailCliente').value = booking.email_cliente || '';
                document.getElementById('editNumViajeros').value = booking.num_viajeros || '';
                document.getElementById('editIdVehiculo').value = booking.id_vehiculo || '';
                document.getElementById('editIdDestino').value = booking.id_destino || '';
                document.getElementById('editTipoCreadorReserva').value = booking.tipo_creador_reserva || ''; //añadido
                // Mostrar los campos específicos según el tipo de reserva
                mostrarCampos("edit");

                // Campos específicos para Aeropuerto - Hotel
                if (booking.id_tipo_reserva == 1 || booking.id_tipo_reserva == 'idayvuelta') {
                    document.getElementById('editFechaEntrada').value = booking.fecha_entrada || '';
                    document.getElementById('editHoraEntrada').value = booking.hora_entrada || '';
                    document.getElementById('editNumeroVueloEntrada').value = booking.numero_vuelo_entrada || '';
                    document.getElementById('editOrigenVueloEntrada').value = booking.origen_vuelo_entrada || '';
                }

                // Campos específicos para Hotel - Aeropuerto
                if (booking.id_tipo_reserva == 2 || booking.id_tipo_reserva == 'idayvuelta') {
                    document.getElementById('editFechaVueloSalida').value = booking.fecha_vuelo_salida || '';
                    document.getElementById('editHoraVueloSalida').value = booking.hora_vuelo_salida || '';
                }

                // Mostrar el modal de actualización
                var modal = new bootstrap.Modal(document.getElementById('editBookingModal'));
                modal.show();
            })
            .catch(error => {
                console.error('Error al obtener los datos de la reserva:', error);
            });
    }

    function abrirModalActualizarReserva(idReserva) {
        editBookingForm(idReserva);
    }

    function abrirModalActualizar(traveler) {
        document.querySelector('#updateIdTravelerInput').value = traveler.id_viajero;
        document.querySelector('#updateEmailInput').value = traveler.email || '';
        document.querySelector('#updateNameInput').value = traveler.nombre || '';
        document.querySelector('#updateSurname1Input').value = traveler.apellido1 || '';
        document.querySelector('#updateSurname2Input').value = traveler.apellido2 || '';
        document.querySelector('#updateAddressInput').value = traveler.direccion || '';
        document.querySelector('#updateZipCodeInput').value = traveler.codigopostal || '';
        document.querySelector('#updateCityInput').value = traveler.ciudad || '';
        document.querySelector('#updateCountryInput').value = traveler.pais || '';

        var modal = new bootstrap.Modal(document.getElementById('updateTravelerModal'));
        modal.show();
    }
</script>

@endsection
