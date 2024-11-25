@extends('layouts.admin')

@section('title', 'Gestión de Excursiones')

@section('content')
<div class="d-flex flex-column vh-100">
    <!-- Título -->
    <header class="text-secondary text-center p-4 fs-1">
        <h1 class="shadow-sm">Gestión de Excursiones</h1>
    </header>
    <div class="col text-start pb-2 px-4">
        <button class="btn btn-outline-secondary fw-bold" data-bs-toggle="modal" data-bs-target="#createTourModal">
            <i class="bi bi-plus-circle"></i> Nueva Excursión
        </button>
    </div>

    <div class="flex-grow-1 overflow-auto ms-2">
        <div class="table-responsive">
            <table class="table table-light table-striped table-hover">
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
                        <th>Acciones</th>
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
                                <form action="{{ route('admin.tours.destroy', $tour) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger m-1" title="Eliminar excursión">
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
    function setEditTour(tour) {
    document.getElementById('editFecha').value = tour.fecha_excursion;
    document.getElementById('editHoraEntrada').value = tour.hora_entrada_excursion;
    document.getElementById('editHoraSalida').value = tour.hora_salida_excursion;
    document.getElementById('editDescripcion').value = tour.descripcion;
    document.getElementById('editNumExcursionistas').value = tour.num_excursionistas;
    document.getElementById('editEmailCliente').value = tour.email_cliente;
    document.getElementById('editHotel').value = tour.id_hotel;
    document.getElementById('editVehiculo').value = tour.id_vehiculo || ''; // Asignar vacío si no hay vehículo
    document.getElementById('editTourForm').action = `/admin/tours/${tour.id_excursion}`;
}

</script>
@endsection
