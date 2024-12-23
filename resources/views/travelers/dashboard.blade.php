@extends('layouts.traveler')

@section('content')

<div class="container d-flex justify-content-center align-items-center flex-column">
    <div class="col-xl-12 text-center">
        <!-- Subtítulo -->
        <h2 class="text-secondary fw-light pt-3 fs-3">Gestiona tus transfers</h2>

        <!-- Botón de crear reserva -->
        <div class="fw-bold pt-3">
            <button type="button" class="btn btn-lg text-warning" data-bs-toggle="modal" data-bs-target="#addBookingModal">
                <i class="fa-solid fa-calendar-plus display-5"></i>
            </button>
        </div>
    </div>

    <!-- Calendario -->
    <div class="col-xl-8 col-lg-10 col-md-12 text-center m-3">
        <div id="calendar" class="bg-white border rounded-2 p-3"></div>
    </div>
</div>

    @include ('travelers.partials.create')
    @include('travelers.partials.edit')
    @include('travelers.partials.delete')
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

        // Define las funciones en el contexto global
        window.abrirModalEditar = function(id) {
            fetch(`{{ url('traveler/bookings') }}/${id}`)
                .then(response => {
                    if (!response.ok) {
                        if (response.status === 404) {
                            throw new Error('Booking not found');
                        }
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Booking data:', data); // Log the response data for debugging

                    // Configurar los campos del modal
                    document.getElementById('editIdReserva').value = data.id_reserva || '';
                    document.getElementById('editLocalizador').value = data.localizador || '';
                    document.getElementById('editIdTipoReserva').value = data.id_tipo_reserva || '';
                    document.getElementById('editEmailCliente').value = data.email_cliente || '';
                    document.getElementById('editNumViajeros').value = data.num_viajeros || '';
                    document.getElementById('editIdDestino').value = data.id_destino || '';
                    document.getElementById('editIdVehiculo').value = data.id_vehiculo || '';

                    // Set the form action URL
                    document.getElementById('editBookingForm').action = `{{ url('traveler/bookings') }}/${data.id_reserva}`;

                    // Mostrar campos específicos
                    mostrarCampos('edit');

                    if (data.id_tipo_reserva == 1) {
                        document.getElementById('editFechaEntrada').value = data.fecha_entrada || '';
                        document.getElementById('editHoraEntrada').value = data.hora_entrada || '';
                        document.getElementById('editNumeroVueloEntrada').value = data.numero_vuelo_entrada || '';
                        document.getElementById('editOrigenVueloEntrada').value = data.origen_vuelo_entrada || '';
                    } else if (data.id_tipo_reserva == 2) {
                        document.getElementById('editFechaVueloSalida').value = data.fecha_vuelo_salida || '';
                        document.getElementById('editHoraVueloSalida').value = data.hora_vuelo_salida || '';
                    }

                    // Cerrar el modal de SweetAlert2
                    Swal.close();

                    // Mostrar el modal
                    const modal = new bootstrap.Modal(document.getElementById('editBookingModal'));
                    modal.show();
                })
                .catch(error => {
                    console.error('Error fetching booking data:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.message === 'Booking not found' ? 'La reserva no fue encontrada.' : error.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                });
        }

        window.eliminarReserva = function(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`{{ url('traveler/bookings') }}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }).then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    }).then(data => {
                        if (data.success) {
                            // Mostrar mensaje de éxito
                            Swal.fire({
                                icon: 'success',
                                title: 'Reserva eliminada',
                                text: 'La reserva ha sido eliminada correctamente.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            window.calendar.refetchEvents();
                        } else {
                            // Mostrar mensaje de error
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    }).catch(error => {
                        console.error('Error al eliminar la reserva:', error);
                        // Mostrar mensaje de error
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Hubo un error al intentar eliminar la reserva.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    });
                }
            });
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
