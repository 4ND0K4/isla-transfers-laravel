<!-- Estilos CSS FullCalendar -->
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
        background-color: #d1ecf1 !important;
        color: #000000 !important;
    }
    .fc .fc-toolbar-title {
        color: #85c1e9 !important;
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
        background-color: #fff3cd !important;
        color: #000000 !important;
    }
</style>
<style>
    .fc-col-header-cell a {
        text-decoration: none !important; /* Elimina el subrayado de los encabezados de los días: lunes, martes... */
}
</style>
<!-- Calendario FullCalendar -->
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
