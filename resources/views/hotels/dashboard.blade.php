@extends('layouts.hotel')

@section('title', 'Panel de Hotel')

@section('content')

<div class="container my-4">
    <div class="row">
        <h1 class="text-center mb-4">Bienvenido, {{ $hotel->usuario }}</h1>
    </div>

    <div class="row">
        <!-- Gráfico -->
        <div class="col-md-6 col-lg-6 mb-4">
            <div class="bg-white border rounded-2 p-3 shadow" style="height: 100%; max-height: 100vh;">
                <h2 class="text-center">Comparación de Comisiones</h2>
                <div style="height: 400px; max-height: 100%; overflow: hidden;">
                    {!! $chart->container() !!}
                </div>
            </div>
        </div>

        <!-- Tabla de Comisiones -->
        <div class="col-md-6 col-lg-6 mb-4">
            <div class="bg-white border rounded-2 p-3 shadow">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="mb-0">Comisiones Mensuales</h2>
                    <button type="button" class="btn btn-outline-secondary fw-bold" data-bs-toggle="modal" data-bs-target="#addBookingModal">
                        <i class="bi bi-plus-circle"></i> Nueva reserva
                    </button>
                </div>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Año</th>
                            <th>Mes</th>
                            <th>Comisión Total (€)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($comisiones as $comision)
                            <tr>
                                <td>{{ $comision->year }}</td>
                                <td>{{ $comision->month }}</td>
                                <td>{{ number_format($comision->total_comision, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">No hay comisiones disponibles</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
{{ $chart->script() }}
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
</script>
@endsection

