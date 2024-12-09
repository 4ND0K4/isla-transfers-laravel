<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    @vite(['resources/css/app.scss', 'resources/js/app.js'])

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
        const calendarEl = document.getElementById('calendar');

        const calendar = new FullCalendar.Calendar(calendarEl, {
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
            events: function(fetchInfo, successCallback, failureCallback) {
                fetch("{{ route('traveler.calendar.events') }}")
                    .then(response => response.json())
                    .then(events => {
                        // Agregar eventos al calendario
                        successCallback(events);
                    })
                    .catch(error => {
                        console.error('Error al cargar eventos:', error);
                        failureCallback(error);
                    });
            },
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
                    </div>
                        `,
                    icon: 'info',
                    showCloseButton: true,
                    confirmButtonText: '<span style="color: white; font-weight: bold;">Cerrar</span>',
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
                            confirmButton.style.backgroundColor = '#6c757d'; // Color fondo
                            confirmButton.style.color = 'white'; // Color texto
                            confirmButton.style.fontSize = '16px'; // Tamaño de fuente
                            confirmButton.style.fontWeight = 'bold'; // Negrita
                            confirmButton.style.fontFamily = 'Arial, sans-serif'; // Fuente
                            confirmButton.style.padding = '10px 20px'; // Padding
                            confirmButton.style.borderRadius = '8px'; // Bordes redondeados
                            confirmButton.style.border = '2px solid #6c757d'; // Color borde
                            confirmButton.style.boxShadow = '0px 4px 10px rgba(0, 0, 0, 0.2)'; // Sombra
                            confirmButton.style.transition = 'all 0.3s ease'; // Transición
                            confirmButton.style.margin = '10px'; // Espacio externo

                            // Efecto hover
                            confirmButton.onmouseover = () => {
                                confirmButton.style.backgroundColor = '#e2e3e5'; // Cambio de color en hover
                                confirmButton.style.transform = 'scale(1.05)'; // Efecto de aumento
                            };
                            confirmButton.onmouseout = () => {
                                confirmButton.style.backgroundColor = '#6c757d';
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
            editBookingModal(idReserva); // Abre el modal de Bootstrap para editar la reserva
        }

        // Función para la confirmación de eliminación y cierre de SweetAlert2
        function eliminarReserva(idReserva) {
            Swal.close(); // Cierra el modal de SweetAlert2
            confirmarEliminacion(`/controllers/bookings/delete.php?id_booking=${idReserva}`);
        }
</script>
</head>
<body  id="traveler">
    <nav class="navbar navbar-expand-xl bg-white">
        <div class="container-fluid">
            <a class="navbar-brand ps-5" href="#">
                <img src="{{ asset('images/icons/logo_traveler.png') }}" alt="Ícono" width="150" height="50">
            </a>

            <!-- Navegación y botones -->
            <ul class="nav nav-pills justify-content-end">
                <!-- Botón abrir modal perfil -->
                <li class="nav-item text-center">
                    <button
                        class="btn btn-primary bg-transparent border-0 fs-5 fw-bold text-success"
                        onclick="abrirModalActualizar({
                            id_viajero: {{ $traveler->id_viajero }},
                            email: '{{ $traveler->email }}',
                            nombre: '{{ $traveler->nombre }}',
                            apellido1: '{{ $traveler->apellido1 }}',
                            apellido2: '{{ $traveler->apellido2 }}',
                            direccion: '{{ $traveler->direccion }}',
                            codigopostal: '{{ $traveler->codigopostal }}',
                            ciudad: '{{ $traveler->ciudad }}',
                            pais: '{{ $traveler->pais }}'
                        })">
                        <i class="bi bi-person-gear px-2 text-success"></i>Perfil
                    </button>
                </li>
                <!-- Botón Cerrar sesión -->
                <li class="pt-2">
                    <form action="{{ route('traveler.logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="fs-5 px-3 text-decoration-none text-danger" style="background: none; border: none;">
                            <i class="bi bi-box-arrow-left fs-5"></i> Cerrar sesión
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>
    <main class="container-fluid px-0"></main>
        @yield('content') <!-- Aquí se inyectará el contenido de las vistas -->
    </main>

    @include('travelers.partials.edit')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</body>
</html>
