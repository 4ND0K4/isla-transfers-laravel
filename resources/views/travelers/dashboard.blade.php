@extends('layouts.traveler')

@section('content')

    <div class="container justify-content-center align-items-center">
        <div class="col-xl-12 text-center">
            <!-- Subtítulo -->
            <h2 class="text-secondary fw-light pt-3 fs-3">Gestiona tus transfers</h2>
            <!-- Botón de crear reserva -->
            <div class="fw-bold py-3">
                <button type="button" class="btn btn-lg text-warning" data-bs-toggle="modal" data-bs-target="#addBookingModal">
                    <i class="fa-solid fa-calendar-plus display-5"></i>
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
    @include('travelers.partials.delete')
    @include('travelers.partials.profile')

    <!-- Modal de Confirmación de Eliminación -->
    <div class="modal fade" id="confirmarEliminacionModal" tabindex="-1" aria-labelledby="confirmarEliminacionLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning-subtle">
                    <h2 class="modal-title" id="confirmarEliminacionLabel">Confirmar Eliminación</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light">
                    ¿Estás seguro de que deseas eliminar esta reserva?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" id="btnEliminar" class="btn btn-danger" data-url="">Eliminar</button>
                </div>
            </div>
        </div>
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

        function abrirModalEditar(id) {
            fetch(`/traveler/bookings/${id}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Configurar los campos del modal
                    document.getElementById('editIdReserva').value = data.id_reserva || '';
                    document.getElementById('editLocalizador').value = data.localizador || '';
                    document.getElementById('editIdTipoReserva').value = data.id_tipo_reserva || '';
                    document.getElementById('editEmailCliente').value = data.email_cliente || '';
                    document.getElementById('editNumViajeros').value = data.num_viajeros || '';
                    document.getElementById('editIdDestino').value = data.id_destino || '';
                    document.getElementById('editIdVehiculo').value = data.id_vehiculo || '';

                    // Set the form action URL
                    document.getElementById('editBookingForm').action = `/traveler/bookings/${id}`;

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
                });
        }

        function eliminarReserva(id) {
            const url = `/traveler/bookings/${id}`;
            confirmarEliminacion(url);
        }

        function confirmarEliminacion(url) {
            const btnEliminar = document.getElementById('btnEliminar');
            btnEliminar.setAttribute('data-url', url);

            btnEliminar.onclick = function () {
                const urlToDelete = btnEliminar.getAttribute('data-url');
                if (urlToDelete) {
                    fetch(urlToDelete, {
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
                            Swal.fire('Eliminado', 'La reserva ha sido eliminada.', 'success');
                            calendar.refetchEvents();
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    }).catch(error => {
                        console.error('Error al eliminar la reserva:', error);
                        Swal.fire('Error', 'Hubo un error al intentar eliminar la reserva.', 'error');
                    });
                }
            };

            // Cerrar el modal de SweetAlert2
            Swal.close();

            // Mostrar el modal de confirmación de eliminación
            const modal = new bootstrap.Modal(document.getElementById('confirmarEliminacionModal'));
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
