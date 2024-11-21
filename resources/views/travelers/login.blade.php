@extends('layouts.app')

@section('title', 'Login - Isla Transfers')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center">Iniciar Sesión</h1>
    <form action="{{ route('traveler.login') }}" method="POST" class="mx-auto" style="max-width: 400px;">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Correo Electrónico</label>
            <input
                type="email"
                name="email"
                id="email"
                class="form-control"
                placeholder="Introduce tu correo electrónico"
                required
            >
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input
                type="password"
                name="password"
                id="password"
                class="form-control"
                placeholder="Introduce tu contraseña"
                required
            >
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
        </div>

        <!-- Mensajes de error -->
        @if ($errors->any())
            <div class="alert alert-danger mt-3">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Mensajes de éxito -->
        @if (session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
        @endif


    </form>
</div>
@endsection

