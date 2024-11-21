<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Isla Transfers')</title>
    <meta name="author" content="PHPOWER" />
    <meta name="description" content="La página de inicio del panel de Clientes particulares (Viajeros) de Isla Transfer
    es accesible cuando el usuario viajero se identifica con sus credenciales. Desde aquí se puede acceder
    a la gestión de todas las acciones disponibles en la aplicación web para este tipo de usuario" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Paaji+2:wght@400..800&family=Caveat&family=Roboto+Flex:opsz@8..144&display=swap" rel="stylesheet">
    <!-- Enlaces CDN -->
    <!-- Enlaces CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Enlaces Hojas Estilo-->
    <link rel="stylesheet" href="../assets/css/general.css">
    <link rel="stylesheet" href="../assets/css/traveler.css">
    <!-- Estilos para el header del FullCalendario. No funciona desde las hojas externas -->
    <style>
        /* CSS Personalizado para la Barra de Herramientas */
        .fc .fc-prev-button,
        .fc .fc-next-button,
        .fc .fc-today-button {
            background-color: #e2e3e5 !important;
            color: #000000 !important;
            border: none !important;
            font-weight: bold !important;
        }
        .fc .fc-prev-button:hover,
        .fc .fc-next-button:hover,
        .fc .fc-today-button:hover {
            background-color: #fff3cd !important;
            color: #000000 !important;
        }
        .fc .fc-toolbar-title {
            color: #28a745 !important;
            font-size: 1.5em !important;
            font-weight: bold !important;
            font-family: Arial, sans-serif !important;
        }
        .fc .fc-button-group .fc-button {
            background-color: #e2e3e5 !important;
            color: #000000 !important;
            border: none !important;
        }
        .fc .fc-button-group .fc-button:hover {
            background-color: #d4edda !important;
            color: #000000 !important;
        }
    </style>
    <!-- Librería de sweetAlert2-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- FullCalendar.io -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: "es", //idioma
                firstDay: 1, //Inicia en lunes
                //Colocación de los elementos del header
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                //Cambio de nombres del header
                buttonText: {
                    today: 'Hoy',
                    month: 'Mes',
                    week: 'Semana',
                    day: 'Día'
                },
                events: '../controllers/bookings/getCalendar.php',
                //Estilos del today
                dayHeaderContent: function(arg) {
                    let span = document.createElement('span');
                    span.innerText = arg.text;
                    span.style.color = '#28a745';
                    span.style.padding = '5px';
                    span.style.display = 'block';
                    return { domNodes: [span] };
                },
                //Estilos de la celda today en el calendario
                dayCellDidMount: function(info) {
                    if (info.isToday) {
                        info.el.style.backgroundColor = '#fff3cd';
                        info.el.style.color = '#28a745';
                        info.el.style.fontWeight = 'bold';
                    }
                    let dayNumberElement = info.el.querySelector('.fc-daygrid-day-number');
                    if (dayNumberElement) {
                        dayNumberElement.style.color = '#28a745';
                        dayNumberElement.style.fontWeight = 'bold';
                        dayNumberElement.style.textDecoration = 'none';
                    }
                },
                //Estilo para las reservas insertadas en las celdas
                eventDidMount: function(info) {
                    console.log(info.event.extendedProps);
                    // Verifica el creador de la reserva y cambia el color del evento
                    if (info.event.extendedProps.tipo_creador_reserva === 1) {
                        info.el.style.backgroundColor = '#17a2b8'; // Color para reservas creadas por el admin (por ejemplo, gris oscuro)
                        info.el.style.color = '#ffffff';
                    } else if (info.event.extendedProps.tipo_creador_reserva === 2) {
                        info.el.style.backgroundColor = '#ffc107'; // Color para reservas creadas por el traveler (por ejemplo, gris claro)
                        info.el.style.color = '#ffffff';
                    }
                },
                //Estilo de las cards (con sweetAlert2)
                eventClick: function(info) {
                    Swal.fire({
                        title: '<strong style="color: #343a40; font-size: 1em; font-weight: bold;">Detalles de la Reserva</strong>',
                        html: `
                        <p style="color: #6c757d; font-size: 1em; text-align: left; margin-left: 20px;">
                            <strong>Ruta:</strong> <!--Tipo de Reserva-->${info.event.extendedProps.id_tipo_reserva == 1 ? 'Aeropuerto-Hotel' : 'Hotel-Aeropuerto'} -
                            <strong>Origen/Destino:</strong> <!--Hotel-->${info.event.title}
                        </p>
                        <p style="color: #6c757d; font-size: 1em; text-align: left; margin-left: 20px;">
                            <strong>Día:</strong> <!--Día recogida entrada/salida-->${info.event.start.toLocaleDateString()}
                            <strong>Hora:</strong> <!--Hora recogida entrada/salida-->${info.event.start.toLocaleTimeString()}
                        </p>
                        <p style="color: #6c757d; font-size: 1em; text-align: left; margin-left: 20px;">
                            <strong>Nº vuelo:</strong> ${info.event.extendedProps.numero_vuelo_entrada} -
                            <strong>Origen:</strong> ${info.event.extendedProps.origen_vuelo_entrada}
                        </p>
                        <p style="color: #6c757d; font-size: 1em; text-align: left; margin-left: 20px;">
                            <strong>Vehículo:</strong> ${info.event.extendedProps.id_vehiculo} -
                            <strong>Nº viajeros:</strong> ${info.event.extendedProps.num_viajeros}
                        </p>
                        <p style="color: #6c757d; font-size: 1em; text-align: left; margin-left: 20px;">
                            <strong>Cliente:</strong> ${info.event.extendedProps.email_cliente}<br>
                        </p>
                            <hr>
                        <p style="color: #6c757d; font-size: 1em; text-align: left; margin-left: 20px;">
                            <strong>ID:</strong> ${info.event.id} -
                            <strong>Localizador:</strong> ${info.event.extendedProps.localizador}
                        </p>
                            <div class="mt-3">
                                <button onclick="editarReserva(${info.event.id})" class="btn btn-warning">Editar</button>
                                <button onclick="eliminarReserva('${info.event.id}')" class="btn btn-danger">Eliminar</button>
                            </div>`,
                        icon: 'info',
                        showCloseButton: true,
                        confirmButtonText: false,
                        customClass: {
                            popup: 'swal-wide' // Clase personalizada para ajustar el ancho
                        },
                        didOpen: () => {
                            // Estilo de fondo de la card
                            const swalPopup = Swal.getPopup();
                            swalPopup.style.backgroundColor = '#d4edda';  // Color de fondo
                            swalPopup.style.borderRadius = '10px';        // Bordes redondeados
                            swalPopup.style.color = '#343a40';            // Color del texto
                            swalPopup.style.boxShadow = '0px 4px 10px rgba(0, 0, 0, 0.2)'; // Sombra de la tarjeta
                            //Estilo botón Cerrar
                            const confirmButton = Swal.getConfirmButton();
                            confirmButton.style.backgroundColor = '#d4edda'; // Color fondo
                            confirmButton.style.color = 'white'; // Color texto
                            confirmButton.style.fontSize = '16px'; // Tamaño de fuente
                            confirmButton.style.fontWeight = 'bold'; // Negrita
                            confirmButton.style.fontFamily = 'Arial, sans-serif'; // Fuente
                            confirmButton.style.padding = '0px'; // Padding
                            confirmButton.style.borderRadius = '0px'; // Bordes redondeados
                            confirmButton.style.border = '0px'; // Color borde
                            confirmButton.style.boxShadow = '0px'; // Sombra
                            confirmButton.style.transition = 'all 0.3s ease'; // Transición
                            confirmButton.style.margin = '10px'; // Espacio externo

                            // Efecto hover
                            confirmButton.onmouseover = () => {
                                confirmButton.style.backgroundColor = '#6c757d'; // Cambio de color en hover
                                confirmButton.style.transform = 'scale(1.05)'; // Efecto de aumento
                            };
                            confirmButton.onmouseout = () => {
                                confirmButton.style.backgroundColor = '#fff3cd';
                                confirmButton.style.transform = 'scale(1)';
                            };
                            //Estilo icono superior decorativo
                            const iconElement = Swal.getIcon();
                            iconElement.style.color = '#ffc107'; // Cambia el color del ícono
                            iconElement.style.borderColor = '#ffc107'; // Cambia el color del círculo
                        }
                    });
                }
            });
            calendar.render();
        });
        // Función para abrir el modal de actualización y cerrar SweetAlert2
        function editarReserva(idReserva) {
            Swal.close(); // Cierra el modal de SweetAlert2
            abrirModalActualizarReserva(idReserva); // Abre el modal de Bootstrap para editar la reserva
        }

        // Función para la confirmación de eliminación y cierre de SweetAlert2
        function eliminarReserva(idReserva) {
            Swal.close(); // Cierra el modal de SweetAlert2
            confirmarEliminacion(`/controllers/bookings/delete.php?id_booking=${idReserva}`);
        }
    </script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">Isla Transfers</a>
        </div>
    </nav>
    <div class="container mt-4">
        @yield('content') <!-- Aquí se inyectará el contenido de las vistas -->
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


// Función para abrir el modal de edición y llenar los campos
function setEditBooking(booking) {
    // Campos comunes
    document.getElementById('editIdReserva').value = booking.id_reserva || '';
    document.getElementById('editEmailCliente').value = booking.email_cliente || '';
    document.getElementById('editNumViajeros').value = booking.num_viajeros || '';
    document.getElementById('editIdTipoReserva').value = booking.id_tipo_reserva || '';
    document.getElementById('editIdDestino').value = booking.id_destino || '';

    // Mostrar los campos específicos según el tipo de reserva
    mostrarCampos('edit');

    // Aeropuerto - Hotel
    if (booking.id_tipo_reserva == 1 || booking.id_tipo_reserva == "idayvuelta") {
        document.getElementById('editFechaEntrada').value = booking.fecha_entrada || '';
        document.getElementById('editHoraEntrada').value = booking.hora_entrada || '';
        document.getElementById('editNumeroVueloEntrada').value = booking.numero_vuelo_entrada || '';
        document.getElementById('editOrigenVueloEntrada').value = booking.origen_vuelo_entrada || '';
    }

    // Hotel - Aeropuerto
    if (booking.id_tipo_reserva == 2 || booking.id_tipo_reserva == "idayvuelta") {
        document.getElementById('editFechaVueloSalida').value = booking.fecha_vuelo_salida || '';
        document.getElementById('editHoraVueloSalida').value = booking.hora_vuelo_salida || '';
    }

    // Mostrar el modal de edición
    const modal = new bootstrap.Modal(document.getElementById('editBookingModal'));
    modal.show();
}

</script>
    <!-- Agregar JS de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
