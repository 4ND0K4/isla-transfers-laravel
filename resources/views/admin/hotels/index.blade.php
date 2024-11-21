@extends('layouts.admin')

@section('title', 'Gestión de Hoteles')

@section('content')
<div class="container">
    <h1 class="text-center">Gestión de Hoteles</h1>

    <button class="btn btn-success my-3" data-bs-toggle="modal" data-bs-target="#createHotelModal">Nuevo Hotel</button>

    <table class="table table-bordered">
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
                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editHotelModal" onclick="setEditHotel({{ $hotel }})">Editar</button>
                        <form action="{{ route('admin.hotels.destroy', $hotel) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
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
