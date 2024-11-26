<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Dashboard</title>
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
    <!-- Apex charts (para gráficos) -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>


    <!-- Enlaces Hojas Estilo -->
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
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
        document.addEventListener("DOMContentLoaded", function () {
            // Imprimir las variables en la consola del navegador
            console.log('Labels:', @json($labels ?? []));
            console.log('Data:', @json($data ?? []));

            const options = {
                series: [{
                    name: 'Comisiones (€)',
                    data: @json($data ?? []) // Datos calculados: Mes Anterior y Mes Actual
                }],
                chart: {
                    type: 'bar',
                    height: 350
                },
                xaxis: {
                    categories: @json($labels ?? []), // Meses: ["Mes Anterior", "Mes Actual"]
                    title: {
                        text: 'Meses'
                    }
                },
                title: {
                    text: 'Comparación de Comisiones: Mes Anterior vs Mes Actual',
                    align: 'center'
                },
                colors: ['#00E396'],
                dataLabels: {
                    enabled: true
                },
                yaxis: {
                    title: {
                        text: 'Comisiones (€)'
                    }
                },
                tooltip: {
                    shared: true,
                    intersect: false
                }
            };

            const chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();
        });
    </script>
    <!-- Agregar JS de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
