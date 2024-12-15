@extends('layouts.traveler')

@section('content')

    <div class="container justify-content-center align-items-center">
        <div class="col-xl-12 text-center">
            <!-- Subtítulo -->
            <h2 class="text-secondary fw-light pt-3 fs-3">Gestiona tus transfers</h2>
            <!-- Botón de crear reserva -->
            <div class="fw-bold py-3">
                <button type="button" class="btn btn-lg text-warning" data-bs-toggle="modal" data-bs-target="#addBookingModal">
                    <i class="bi bi-journal-plus display-5"></i>
                </button>
            </div>

        </div>
        <!-- Calendario -->
        <div class="col-xl-8 text-center bg-white border rounded-2 p-5 my-5">
            <div
                id="calendar"
                class=" bg-white border rounded-2 py-3 me-1 my-3">
            </div>
        </div>
    </div>

    @include ('travelers.partials.create')
    @include('travelers.partials.edit')
    @include('travelers.partials.profile')

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

        function setEditBooking(idReserva) {
            const booking = JSON.parse(button.getAttribute('data-booking'));
            console.log("Reserva seleccionada:", booking);

            // Actualizar la acción del formulario
            const form = document.getElementById('editBookingForm');
            form.action = `/traveler/bookings/${booking.id_reserva}`; // Actualiza la ruta con el id_reserva correcto

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

        function abrirModalActualizar(traveler) {
        document.querySelector('#updateIdTravelerInput').value = traveler.id_viajero;
        document.querySelector('#updateEmailInput').value = traveler.email || '';
        document.querySelector('#updateNameInput').value = traveler.nombre || '';
        document.querySelector('#updateSurname1Input').value = traveler.apellido1 || '';
        document.querySelector('#updateSurname2Input').value = traveler.apellido2 || '';
        document.querySelector('#updateAddressInput').value = traveler.direccion || '';
        document.querySelector('#updateZipCodeInput').value = traveler.codigo_postal || '';
        document.querySelector('#updateCityInput').value = traveler.ciudad || '';
        document.querySelector('#updateCountryInput').value = traveler.pais || '';

        var modal = new bootstrap.Modal(document.getElementById('updateTravelerModal'));
        modal.show();
    }


    </script>
@endsection
