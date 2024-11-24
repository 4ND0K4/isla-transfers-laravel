@extends('layouts.admin')

@section('title', 'Gestión de Excursiones')

@section('content')
<div class="d-flex flex-column vh-100">
    <!-- Título -->
    <header class="text-secondary text-center b text-info p-4">
        <h1 class="shadow">Gestión de Excursiones</h1>
    </header>
    <div class="col text-start pb-2 px-4">
        <button class="btn btn-outline-info fw-bold" data-bs-toggle="modal" data-bs-target="#createTourModal">Nueva Excursión</button>
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
                            <td>{{ $tour->id_hotel }}</td>
                            <td>{{ $tour->id_vehiculo }}</td>
                            <td>
                                <button
                                class="btn btn-sm btn-outline-warning m-1"
                                title="Editar excursión"
                                data-bs-toggle="modal"
                                data-bs-target="#editTourModal"
                                onclick="setEditTour({{ $tour }})">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                                <form action="{{ route('admin.tours.destroy', $tour) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="btn btn-sm btn-outline-danger m-1"
                                        title="Eliminar excursión">
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
        document.getElementById('editTourForm').action = '/admin/tours/' + tour.id_excursion;
    }
</script>
@endsection
