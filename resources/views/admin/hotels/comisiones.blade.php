@extends('layouts.admin')

@section('title', 'Comisiones de Hoteles')

@section('content')
<div class="container">
    <h1>Comisiones del Hotel: {{ $hotel->nombre ?? 'Hotel no encontrado' }}</h1>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Año</th>
                <th>Mes</th>
                <th>Total Comisión</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($comisiones as $comision)
                <tr>
                    <td>{{ $comision->year }}</td>
                    <td>{{ $comision->month }}</td>
                    <td>{{ number_format($comision->total_comision, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
