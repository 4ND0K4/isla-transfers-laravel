@extends('layouts.admin')

@section('title', 'Gestión de Excursiones')

@section('content')
<div class="d-flex flex-column vh-100">
    <!-- Título -->
    <header class="text-secondary text-center p-4 fs-1">
        <h1 class="shadow-sm">Gestión de Excursiones</h1>
    </header>
    <!-- Mensajes de error y éxito -->
    @if ($errors->any())
        <div class="alert alert-danger" id="error-messages">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success" id="success-message">
            {{ session('success') }}
        </div>
    @endif
    <div class="col text-start pb-2 px-4">
        <button class="btn btn-outline-secondary fw-bold" data-bs-toggle="modal" data-bs-target="#createTourModal">
            <i class="bi bi-plus-circle"></i> Nueva Excursión
        </button>
    </div>

    <div class="flex-grow-1 overflow-auto ms-2">
        <div class="table-responsive">
            <table class="table table-light table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Hora Entrada</th>
                        <th>Hora Salida</th>
                        <th>Descripción</th>
                        <th># Excursionistas</th>
                        <th>Email Cliente</th>
                        <th>Hotel</th>
                        <th>Vehículo</th>
                        <th><i class="bi bi-gear-fill"></i></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tours as $tour)
                        <tr>
                            <td>{{ $tour->id_excursion }}</td>
                            <td>{{ $tour->fecha_excursion }}</td>
                            <td>{{ $tour->hora_entrada_excursion }}</td>
                            <td>{{ $tour->hora_salida_excursion }}</td>
                            <td>{{ $tour->descripcion }}</td>
                            <td>{{ $tour->num_excursionistas }}</td>
                            <td>{{ $tour->email_cliente }}</td>
                            <td>{{ $tour->hotel->id_hotel ?? 'Hotel desconocido' }}</td>
                            <td>{{ $tour->vehicle->descripcion ?? 'Vehículo no asignado' }}</td>
                            <td>
                                <button
                                    class="btn btn-sm btn-outline-warning m-1"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editTourModal"
                                    title="Editar excursión"
                                    onclick="setEditTour({
                                        id_excursion: {{ $tour->id_excursion }},
                                        fecha_excursion: '{{ $tour->fecha_excursion }}',
                                        hora_entrada_excursion: '{{ $tour->hora_entrada_excursion }}',
                                        hora_salida_excursion: '{{ $tour->hora_salida_excursion }}',
                                        descripcion: '{{ $tour->descripcion }}',
                                        num_excursionistas: {{ $tour->num_excursionistas }},
                                        email_cliente: '{{ $tour->email_cliente }}',
                                        id_hotel: {{ $tour->id_hotel }},
                                        id_vehiculo: {{ $tour->id_vehiculo ?? 'null' }}
                                    })">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <form action="{{ route('admin.tours.destroy', $tour) }}" method="POST" style="display: inline;" onsubmit="showSpinner(this)">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger m-1" title="Eliminar excursión">
                                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('admin.tours.partials.create')
@include('admin.tours.partials.edit')

<script>
     document.addEventListener('DOMContentLoaded', function () {
        const editTourForm = document.querySelector('#editTourForm');
        const editTourButton = document.querySelector('#editTourForm button[type="submit"]');
        const loadingSpinnerEdit = document.getElementById('loadingSpinnerEdit');

        editTourForm.addEventListener('submit', function () {
            loadingSpinnerEdit.style.display = 'inline-block';
            editTourButton.disabled = true;
        });
    });
    function setEditTour(tour) {
        document.getElementById('editIdExcursion').value = tour.id_excursion;
        document.getElementById('editFecha').value = tour.fecha_excursion;
        document.getElementById('editHoraEntrada').value = tour.hora_entrada_excursion;
        document.getElementById('editHoraSalida').value = tour.hora_salida_excursion;
        document.getElementById('editDescripcion').value = tour.descripcion;
        document.getElementById('editNumExcursionistas').value = tour.num_excursionistas;
        document.getElementById('editEmailCliente').value = tour.email_cliente;
        document.getElementById('editHotel').value = tour.id_hotel;
        document.getElementById('editVehiculo').value = tour.id_vehiculo || ''; // Asignar vacío si no hay vehículo
        document.getElementById('editTourForm').action = `{{ url('admin/tours') }}/${tour.id_excursion}`;

    }

    function showSpinner(form) {
        const button = form.querySelector('button[type="submit"]');
        const spinner = button.querySelector('.spinner-border');
        spinner.classList.remove('d-none');
        button.disabled = true;
    }
</script>
@endsection
