@extends('layouts.hotel')

@section('title', 'Comisiones del Hotel')

@section('content')
<div class="container my-4">
    <div class="row">
        <h1 class="text-center mb-4">Comisiones del Hotel</h1>
    </div>
    <div class="row">
        <div class="col-12">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Año</th>
                        <th>Mes</th>
                        <th>Comisión Total (€)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($comisiones as $comision)
                        <tr>
                            <td>{{ $comision->year }}</td>
                            <td>{{ $comision->month }}</td>
                            <td>{{ number_format($comision->total_comision, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">No hay comisiones disponibles</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

