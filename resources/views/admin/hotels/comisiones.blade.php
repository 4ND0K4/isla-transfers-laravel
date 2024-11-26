@extends('layouts.admin')

@section('title', 'Comisiones de Hoteles')

@section('content')
<div class="container my-4">
    <h1 class="mb-4">Comisiones de Hoteles</h1>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Hotel</th>
                <th>Año</th>
                <th>Mes</th>
                <th>Comisión Total (€)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($comisiones as $comision)
                <tr>
                    <td>{{ $comision->hotel_nombre }}</td>
                    <td>{{ $comision->year }}</td>
                    <td>{{ \Carbon\Carbon::create()->month($comision->month)->format('F') }}</td>
                    <td>{{ number_format($comision->total_comision, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No hay datos de comisiones disponibles.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
