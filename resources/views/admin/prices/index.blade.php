@extends('layouts.admin')

@section('title', 'Gestión de Precios')

@section('content')
<div class="container">
    <h1 class="mb-4">Gestión de Precios</h1>

    <!-- Formulario para crear un precio -->
    <div class="mb-4">
        <form action="{{ route('admin.prices.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <label for="id_hotel" class="form-label">Hotel</label>
                    <select name="id_hotel" id="id_hotel" class="form-select" required>
                        @foreach($hotels as $hotel)
                            <option value="{{ $hotel->id_hotel }}">{{ $hotel->id_hotel }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="id_vehiculo" class="form-label">Vehículo</label>
                    <select name="id_vehiculo" id="id_vehiculo" class="form-select" required>
                        @foreach($vehicles as $vehicle)
                            <option value="{{ $vehicle->id_vehiculo }}">{{ $vehicle->id_vehiculo }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="precio" class="form-label">Precio (€)</label>
                    <input type="number" name="precio" id="precio" class="form-control" step="0.01" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Crear Precio</button>
        </form>
    </div>

    <!-- Tabla de precios -->
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Hotel</th>
                    <th>Vehículo</th>
                    <th>Precio (€)</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($prices as $price)
                    <tr>
                        <td>{{ $price->hotel->id_hotel }}</td>
                        <td>{{ $price->vehicle->id_vehiculo }}</td>
                        <td>{{ number_format($price->precio, 2) }}</td>
                        <td>
                            <form action="{{ route('admin.prices.destroy', $price) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
