@extends('layouts.admin')

@section('title', 'Gestión de Precios')

@section('content')
<div class="container">
    <header class="text-secondary text-center p-4 fs-1">
        <h1 class="shadow-sm">Gestión de Tarifas</h1>
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
    <!-- Formulario para crear un precio -->
    <div class="mb-4">
        <form action="{{ route('admin.prices.store') }}" method="POST" onsubmit="showSpinner(this)">
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
            <button type="submit" class="btn btn-success mt-3">Crear Precio</button>
            <div id="loadingSpinnerCreate" style="display: none;">
                <div class="spinner-border text-secondary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
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
                    <th><i class="bi bi-gear-fill"></i></th>
                </tr>
            </thead>
            <tbody>
                @foreach($prices as $price)
                    <tr>
                        <td>{{ $price->hotel->id_hotel }}</td>
                        <td>{{ $price->vehicle->id_vehiculo }}</td>
                        <td>{{ number_format($price->precio, 2) }}</td>
                        <td>
                            <form action="{{ route('admin.prices.destroy', $price) }}" method="POST" style="display: inline;" onsubmit="showSpinner(this)">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    function showSpinner(form) {
        const button = form.querySelector('button[type="submit"]');
        const spinner = button.querySelector('.spinner-border');
        spinner.classList.remove('d-none');
        button.disabled = true;
    }

    document.addEventListener('DOMContentLoaded', function () {
        const createPriceForm = document.querySelector('form[action="{{ route('admin.prices.store') }}"]');
        const createPriceButton = createPriceForm.querySelector('button[type="submit"]');
        const loadingSpinnerCreate = document.getElementById('loadingSpinnerCreate');

        createPriceForm.addEventListener('submit', function () {
            loadingSpinnerCreate.style.display = 'block';
            createPriceButton.disabled = true;
        });

        // Ocultar mensajes de error y éxito después de 5 segundos
        setTimeout(function () {
            const errorMessages = document.getElementById('error-messages');
            const successMessage = document.getElementById('success-message');
            if (errorMessages) {
                errorMessages.style.display = 'none';
            }
            if (successMessage) {
                successMessage.style.display = 'none';
            }
        }, 5000);
    });
</script>
@endsection
