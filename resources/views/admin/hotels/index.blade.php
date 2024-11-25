@extends('layouts.admin')

@section('title', 'Gestión de Hoteles')

@section('content')
<div class="d-flex flex-column vh-100">
    <header class="text-secondary text-center p-4 fs-1">
        <h1 class="shadow-sm">Gestión de Hoteles</h1>
    </header>
    <div class="col text-start pb-2 px-4">
        <button class="btn btn-outline-secondary fw-bold" data-bs-toggle="modal" data-bs-target="#createHotelModal"><i class="bi bi-plus-circle"></i> Nuevo Hotel</button>
    </div>
    <!-- Tabla -->
    <div class="flex-grow-1 overflow-auto ms-2">
        <div class="table-responsive">
            <table class="table table-light table-striped table-hover">
                <thead>
                    <tr>
                        <th>Hotel</th>
                        <th>Zona</th>
                        <th>Comisión</th>
                        <th>Usuario</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($hotels as $hotel)
                        <tr>
                            <td>{{ $hotel->id_hotel }}</td>
                            <td>{{ $hotel->id_zona }}</td>
                            <td>{{ $hotel->comision }}</td>
                            <td>{{ $hotel->usuario }}</td>
                            <td>
                                <button
                                class="btn btn-sm btn-outline-warning m-1"
                                title="Editar hotel"
                                data-bs-toggle="modal"
                                data-bs-target="#editHotelModal"
                                onclick="setEditHotel({{ $hotel }})">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                                <form action="{{ route('admin.hotels.destroy', $hotel) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                    type="submit"
                                    class="btn btn-sm btn-outline-danger m-1"
                                    title="Eliminar hotel">
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

@include('admin.hotels.partials.create')
@include('admin.hotels.partials.edit')

<script>
    function setEditHotel(hotel) {
        document.getElementById('editIdZona').value = hotel.id_zona;
        document.getElementById('editComision').value = hotel.comision;
        document.getElementById('editUsuario').value = hotel.usuario;
        document.getElementById('editHotelForm').action = '/admin/hotels/' + hotel.id_hotel;
    }
</script>
@endsection
