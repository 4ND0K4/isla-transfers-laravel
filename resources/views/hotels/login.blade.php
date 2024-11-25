@extends('layouts.app')

@section('title', 'Login Hotel')

@section('content')
<div class="container">
    <h1 class="text-center">Iniciar Sesión - Hoteles</h1>
    <form action="{{ route('hotel.login.post') }}" method="POST" class="mx-auto" style="max-width: 400px;">
        @csrf
        <div class="mb-3">
            <label for="usuario" class="form-label">Usuario</label>
            <input type="text" name="usuario" id="usuario" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
    </form>
</div>
@endsection
