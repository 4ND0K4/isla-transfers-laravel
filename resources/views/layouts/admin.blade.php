<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Administración</title>
    <meta name="author" content="PHPOWER" />
    <meta name="description" content="La página de inicio del panel de administración de Isla Transfer
    es accesible cuando el administrador se identifica con sus credenciales. Desde aquí se puede acceder
    a la gestión de todas las acciones disponibles en la aplicación web" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Flex:opsz@8..144&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Icons Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Enlaces Hojas Estilo -->
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- FullCalendar -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <!-- Implantación del calendario -->
    <style>
        .fc-col-header-cell a {
            text-decoration: none !important; /* Elimina el subrayado de los encabezados de los días: lunes, martes... */
    }
    </style>
    <script>
        // Evento DOMContentLoaded asegura que el DOM esté completamente cargado antes de ejecutar el script
        document.addEventListener('DOMContentLoaded', function() {
            // Seleccionamos el elemento donde se renderizará el calendario
            var calendarEl = document.getElementById('calendar');
            // Inicializamos el calendario FullCalendar
            var calendar = new FullCalendar.Calendar(calendarEl, {
                // Configuración del calendario
                initialView: 'dayGridMonth', // Vista inicial del calendario
                locale: "es", // Idioma en español
                firstDay: 1, // Primer día de la semana (Lunes)
                // Configuración de los botones del header del calendario
                headerToolbar: {
                    left: 'prev,next today', // Botones a la izquierda
                    center: 'title',         // Título centrado
                    right: 'dayGridMonth,timeGridWeek,timeGridDay' // Botones a la derecha
                },
                // Traducción de los textos de los botones
                buttonText: {
                    today: 'Hoy',
                    month: 'Mes',
                    week: 'Semana',
                    day: 'Día'
                },
                // Ruta para obtener los eventos desde el controlador
                events: "{{ route('admin.calendar.events') }}",
                // Personalización del encabezado de los días
                dayHeaderContent: function(arg) {
                    let span = document.createElement('span');
                    span.innerText = arg.text;
                    span.style.color = '#343a40'; // Color del texto
                    span.style.padding = '5px';  // Espaciado interno
                    span.style.display = 'block'; // Bloque para estilos consistentes
                    return { domNodes: [span] };
                },
                // Personalización de las celdas del calendario (e.g., hoy)
                dayCellDidMount: function(info) {
                    if (info.isToday) {
                        info.el.style.backgroundColor = '#d1ecf1'; // Fondo para el día actual
                        info.el.style.color = '#343a40'; // Color de texto
                        info.el.style.fontWeight = 'bold'; // Negrita
                    }
                    // Personalización del número de los días
                    let dayNumberElement = info.el.querySelector('.fc-daygrid-day-number');
                    if (dayNumberElement) {
                        dayNumberElement.style.color = '#343a40';
                        dayNumberElement.style.fontWeight = 'bold';
                        dayNumberElement.style.textDecoration = 'none';
                    }
                },
                // Personalización de los eventos según su tipo
                eventDidMount: function(info) {
                    if (info.event.extendedProps.id_tipo_reserva == 1) {
                        info.el.style.backgroundColor = '#0d6efd'; // Color azul para tipo 1
                        info.el.style.color = '#ffffff'; // Texto blanco
                    } else if (info.event.extendedProps.id_tipo_reserva == 2) {
                        info.el.style.backgroundColor = '#dc3545'; // Color rojo para tipo 2
                        info.el.style.color = '#ffffff'; // Texto blanco
                    }
                },
                // Configuración al hacer clic en un evento (detalles)
                eventClick: function(info) {
                    Swal.fire({
                        // Título del modal con SweetAlert2
                        title: '<strong style="color: #343a40; font-size: 1em; font-weight: bold;">Detalles de la Reserva</strong>',
                        html: `
                        <p style="color: #6c757d; font-size: 1em; text-align: left; margin-left: 20px;">
                            <strong>Ruta:</strong> ${info.event.extendedProps.id_tipo_reserva == 1 ? 'Aeropuerto-Hotel' : 'Hotel-Aeropuerto'} -
                            <strong>Origen/Destino:</strong> ${info.event.title}
                        </p>
                        <p style="color: #6c757d; font-size: 1em; text-align: left; margin-left: 20px;">
                            <strong>Día:</strong> ${info.event.start.toLocaleDateString()} -
                            <strong>Hora:</strong> ${info.event.start.toLocaleTimeString()}
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
                        </p>`,
                        icon: 'info', // Ícono de información
                        confirmButtonText: '<span style="color: white; font-weight: bold;">Cerrar</span>',
                        customClass: {
                            popup: 'swal-wide' // Clase personalizada para ajustar el ancho
                        },
                        didOpen: () => {
                            // Personalización del botón de confirmación
                            const confirmButton = Swal.getConfirmButton();
                            confirmButton.style.backgroundColor = '#6c757d';
                            confirmButton.style.color = 'white';
                            confirmButton.style.fontSize = '16px';
                            confirmButton.style.fontWeight = 'bold';
                            confirmButton.style.borderRadius = '8px';
                            confirmButton.style.transition = 'all 0.3s ease';
                            // Efecto hover
                            confirmButton.onmouseover = () => {
                                confirmButton.style.backgroundColor = '#e2e3e5';
                                confirmButton.style.transform = 'scale(1.05)';
                            };
                            confirmButton.onmouseout = () => {
                                confirmButton.style.backgroundColor = '#6c757d';
                                confirmButton.style.transform = 'scale(1)';
                            };
                        }
                    });
                }
            });

            // Renderizamos el calendario
            calendar.render();
        });
    </script>
</head>
<!-- ///////////////////////////////////////////////// body //////////////////////////////////////////////// -->
<body id="admin">
    <!-- Sidebar -->
    <div class="d-flex">
        <div class="vh-100 d-flex flex-column align-items-center pb-3" id="sidebar">
            <!-- Logo -->
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}" title="Inicio">
                <img src="{{ asset('images/icons/logo_admin.png') }}" alt="Ícono" width="100" height="75">
            </a>
            <!-- Menú -->
            <hr class="text-white w-100">
            <!-- Dashboard -->
            <a  href="{{ route('admin.dashboard') }}" class="text-white text-decoration-none mx-2 mt-3 mb-5 fs-2 hover-icon hover-bg" title="Dashboard">
                <i class="bi bi-grid-3x3-gap"></i>
            </a>
            <!-- Reservas -->
            <a href="{{ route('admin.bookings.index') }}" class="text-white text-decoration-none mx-2 my-3 fs-2 hover-icon hover-bg" title="Reservas">
                <i class="bi bi-calendar-week-fill"></i>
            </a>
            <!-- Excursiones -->
            <a href="{{ route('admin.tours.index') }}" class="text-white text-decoration-none mx-2 my-3 fs-2 hover-icon hover-bg" title="Excursiones">
                <i class="bi bi-backpack2-fill"></i>
            </a>
            <!-- Vehículos -->
            <a href="{{ route('admin.vehicles.index') }}" class="text-white text-decoration-none mx-2 my-3 fs-2 hover-icon hover-bg" title="Vehículos">
                <i class="bi bi-taxi-front-fill"></i>
            </a>
            <!-- Hoteles -->
            <a href="{{ route('admin.hotels.index') }}" class="text-white text-decoration-none mx-2 my-3 fs-2 hover-icon hover-bg" title="Hoteles">
                <i class="bi bi-houses-fill"></i>
            </a>
             <!-- Tarifas -->
             <a href="{{ route('admin.prices.index') }}" class="text-white text-decoration-none mx-2 my-3 fs-2 hover-icon hover-bg" title="Tarifas">
                <i class="bi bi-wallet-fill"></i>
            </a>

        <div class="mt-auto">
            <button class="btn btn-transparent text-danger fs-6" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-left"></i>
                {{ Auth::guard('admins')->user()->usuario ?? 'No identificado' }}
            </button>
            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
    <main class="container-fluid px-0">
        @yield('content')
    </main>
    <!-- Abrir modales en Bootstrap 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
