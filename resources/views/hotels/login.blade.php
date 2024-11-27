@extends('layouts.app')

@section('title', 'Login Hotel')

@section('content')
<!-- BLOQUE PRINCIPAL -->
<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <!-- Imagen decorativa -->
        <div class="col-xl-8">
            <img src="{{ asset('images/login-hotel.jpg') }}" alt="Hamaca en la piscina de un hotel resort con mar de fondo." class="logo img-fluid mb-4 mb-md-0 w-75">
        </div>
        <div class="col-xl-4">
            <!-- Título -->
            <h1 class="fw-bold display-5 pb-5">Tu hotel en Isla Transfer</h1>
            <!-- Subtítulo -->
            <h2 class="fs-5 pb-3">Introduce tus credenciales</h2>
           <form action="{{ route('hotel.login') }}" method="POST">
            @csrf
                <!-- Campo de usuario -->
                <div class="mb-3">
                    <label for="usuario" class="form-label text-success fw-bold">Usuario</label>
                    <input type="text" class="form-control w-75" id="usuario" name="usuario" placeholder="Introduce el usuario" required>
                </div>
                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label text-success fw-bold">Contraseña</label>
                    <input type="password" class="form-control w-75" id="password" name="password" placeholder="Introduce el password" required>
                </div>
                <!-- Botón -->
                <div class="d-grid gap-2 w-75">
                    <button type="submit" class="btn btn-success text-white">Acceder</button>
                </div>
                <!-- Mensaje de error al loguearte -->
                <div class="d-grid gap-2 w-75">
                    <?php if (isset($_SESSION['login_error'])) : ?>
                        <div class="alert alert-danger" role="alert" id="loginError">
                            <?php echo $_SESSION['login_error']; ?>
                            <?php unset($_SESSION['login_error']); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </form>
            <!-- Separador -->
            <div class="d-grid gap-2 w-75">
                <hr>
            </div>
            <!-- Botón (sin acción) para acceder a un formulario de inscripción -->
            <div class="d-grid gap-2 w-75">
                <button type="button" class="btn btn-warning text-white" data-bs-toggle="modal" data-bs-target="#">Formulario de Inscripción</button>
            </div>
        </div>
    </div>
</div>
@endsection
