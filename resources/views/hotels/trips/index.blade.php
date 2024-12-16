@extends('layouts.hotel')

@section('title', 'Excursiones del Hotel')

@section('content')
<div class="container my-4">
    <div class="row">
        <h1 class="text-center mb-4">Excursiones</h1>
    </div>
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
    <div class="row mb-4">
        <div class="col text-start">
            <button class="btn btn-outline-secondary fw-bold" data-bs-toggle="modal" data-bs-target="#createTourModal">
                <i class="bi bi-plus-circle"></i> Nueva Excursión
            </button>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <table class="table table-bordered table-striped text-center">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Hora de Entrada</th>
                        <th>Hora de Salida</th>
                        <th>Descripción</th>
                        <th>Número de Excursionistas</th>
                        <th>Vehículo</th>
                        <th><i class="bi bi-gear-fill"></i></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tours as $tour)
                        <tr>
                            <td>{{ $tour->fecha_excursion }}</td>
                            <td>{{ $tour->hora_entrada_excursion }}</td>
                            <td>{{ $tour->hora_salida_excursion }}</td>
                            <td>{{ $tour->descripcion }}</td>
                            <td>{{ $tour->num_excursionistas }}</td>
                            <td>{{ $tour->vehicle->id_vehiculo ?? 'N/A' }}</td>
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
                                <form action="{{ route('hotel.tours.destroy', $tour) }}" method="POST" style="display: inline;" onsubmit="showSpinner(this)">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger m-1" title="Eliminar excursión">
                                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No hay excursiones disponibles</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('hotels.trips.partials.create')
@include('hotels.trips.partials.edit')

<script>
    function setEditTour(tour) {
        document.getElementById('editFecha').value = tour.fecha_excursion;
        document.getElementById('editHoraEntrada').value = tour.hora_entrada_excursion;
        document.getElementById('editHoraSalida').value = tour.hora_salida_excursion;
        document.getElementById('editDescripcion').value = tour.descripcion;
        document.getElementById('editNumExcursionistas').value = tour.num_excursionistas;
        document.getElementById('editEmailCliente').value = tour.email_cliente;
        document.getElementById('editHotel').value = tour.id_hotel;
        document.getElementById('editVehiculo').value = tour.id_vehiculo || ''; // Asignar vacío si no hay vehículo
        document.getElementById('editTourForm').action = `/hotels/tours/${tour.id_excursion}`;
    }

    function showSpinner(form) {
        const button = form.querySelector('button[type="submit"]');
        const spinner = button.querySelector('.spinner-border');
        spinner.classList.remove('d-none');
        button.disabled = true;
    }
</script>
@endsection
