<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FullCalendar -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

    <!-- Scripts necesarios -->
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
                events: "{{ route('admin.calendar.events') }}",
                //Estilos del today
                dayHeaderContent: function(arg) {
                    let span = document.createElement('span');
                    span.innerText = arg.text;
                    span.style.color = '#343a40';
                    span.style.padding = '5px';
                    span.style.display = 'block';
                    return { domNodes: [span] };
                },
                //Estilos de la celda today en el calendario
                dayCellDidMount: function(info) {
                    if (info.isToday) {
                        info.el.style.backgroundColor = '#e2e3e5';
                        info.el.style.color = '#343a40';
                        info.el.style.fontWeight = 'bold';
                    }
                    let dayNumberElement = info.el.querySelector('.fc-daygrid-day-number');
                    if (dayNumberElement) {
                        dayNumberElement.style.color = '#343a40';
                        dayNumberElement.style.fontWeight = 'bold';
                        dayNumberElement.style.textDecoration = 'none';
                    }
                },
                //Estilo para las reservas insertadas en las celdas
                eventDidMount: function(info) {
                    if (info.event.extendedProps.id_tipo_reserva == 1) {
                        info.el.style.backgroundColor = '#0d6efd';
                        info.el.style.color = '#ffffff'; // Color del texto a blanco
                    } else if (info.event.extendedProps.id_tipo_reserva == 2) {
                        info.el.style.backgroundColor = '#dc3545';
                        info.el.style.color = '#ffffff'; // Color del texto a blanco
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
                        </p>`,
                        icon: 'info',
                        confirmButtonText: '<span style="color: white; font-weight: bold;">Cerrar</span>',
                        customClass: {
                            popup: 'swal-wide' // Clase personalizada para ajustar el ancho
                        },
                        didOpen: () => {
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
                            iconElement.style.color = '#e2e3e5'; // Color del ícono
                            iconElement.style.borderColor = '#e2e3e5'; // Color del círculo
                        }
                    });
                }
            });
            calendar.render();
        });
    </script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">Panel Admin</a>
            <button class="btn btn-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Cerrar Sesión</button>
            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </nav>
    <main class="py-4">
        @yield('content')
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
