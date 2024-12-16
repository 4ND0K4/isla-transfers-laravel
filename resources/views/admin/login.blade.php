@extends('layouts.app')

@section('title', 'Login - Isla Transfers')

@section('content')

<main id="admin-login" class="vh-100 d-flex justify-content-center align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow-lg">
                    <div class="card-header bg-white text-dark text-center border-0">
                        <div class="">
                            <i class="bi bi-person-fill-gear fs-1"></i>
                        </div>
                        <span class="mb-0">Admin login</span>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.login') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="usuario" class="form-label"></label>
                                <input
                                    type="text"
                                    name="usuario"
                                    id="usuario"
                                    class="form-control"
                                    placeholder="Usuario"
                                    required
                                >
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label"></label>
                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    class="form-control"
                                    placeholder="Password"
                                    required
                                >
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-light">Iniciar Sesión</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer border-0">
                        <!-- Mensajes de error -->
                        @if ($errors->any())
                            <div id="error-messages" class="alert alert-danger mt-3 mb-0">
                                <ul class="list-unstyled text-center mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li><i class="fa-regular fa-id-card"></i> {{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection
