<nav class="navbar navbar-expand-xl bg-white">
    <div class="container-fluid">
        <a class="navbar-brand caveat-brush-regular text-success fs-2 ps-5" href="#">
            <img src="{{ asset('images/icons/logo_app.png') }}" alt="Ícono" width="75" height="60">
            Isla Transfers
        </a>
        <ul>
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
        </ul>
        <!-- Navegación y botones -->
        <ul class="nav nav-pills justify-content-end">
            <li class="nav-item text-center">
                 <a class="btn btn-primary bg-transparent border-0 fs-5 fw-bold text-warning disabled">
                    <span class="text-dark fw-light">¡ Hola </span>
                    {{ htmlspecialchars($_SESSION['travelerName'] ?? $traveler->nombre) }}
                    <span class="text-dark fw-light">!</span>
            </a>
        </li>
            <!-- Botón abrir modal perfil -->
            <li class="nav-item text-center">
                <button
                    class="btn btn-primary bg-transparent border-0 fs-5 fw-bold text-success"
                    onclick="abrirModalActualizar({
                        id_viajero: {{ $traveler->id_viajero }},
                        email: '{{ $traveler->email }}',
                        nombre: '{{ $traveler->nombre }}',
                        apellido1: '{{ $traveler->apellido1 }}',
                        apellido2: '{{ $traveler->apellido2 }}',
                        direccion: '{{ $traveler->direccion }}',
                        codigopostal: '{{ $traveler->codigoPostal }}',
                        ciudad: '{{ $traveler->ciudad }}',
                        pais: '{{ $traveler->pais }}'
                    })">
                    <i class="fa-solid fa-user-pen"></i>
                </button>
            </li>
            <!-- Botón Cerrar sesión -->
            <li class="pt-2">
                <form action="{{ route('traveler.logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="fs-5 px-3 text-decoration-none text-danger" style="background: none; border: none;">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </button>
                </form>
            </li>
        </ul>
    </div>
</nav>
