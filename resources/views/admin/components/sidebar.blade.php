<div class="d-flex">
    <div class="vh-100 d-flex flex-column align-items-center py-3 sticky-top" id="sidebar">
        <!-- Logo -->
        <a class="navbar-brand" href="{{ route('admin.dashboard') }}" title="Inicio">
            <img src="{{ asset('images/icons/logo_admin.png') }}" alt="Ícono" width="60" height="60">
        </a>
        <!-- Menú -->
        <hr class="text-white w-100">
        <!-- Dashboard -->
        <a  href="{{ route('admin.dashboard') }}" class="text-white text-decoration-none mx-2 mt-3 mb-5 fs-2 hover-icon hover-bg" title="Dashboard">
            <i class="bi bi-grid-3x3-gap"></i>
        </a>
        <!-- Reservas -->
        <a href="{{ route('admin.bookings.index') }}" class="text-white text-decoration-none mx-2 my-3 fs-2 hover-icon hover-bg" title="Reservas">
            <i class="bi bi-calendar-week-fill"></i>
        </a>
        <!-- Excursiones -->
        <a href="{{ route('admin.tours.index') }}" class="text-white text-decoration-none mx-2 my-3 fs-2 hover-icon hover-bg" title="Excursiones">
            <i class="bi bi-backpack2-fill"></i>
        </a>
        <!-- Vehículos -->
        <a href="{{ route('admin.vehicles.index') }}" class="text-white text-decoration-none mx-2 my-3 fs-2 hover-icon hover-bg" title="Vehículos">
            <i class="bi bi-taxi-front-fill"></i>
        </a>
        <!-- Hoteles -->
        <a href="{{ route('admin.hotels.index') }}" class="text-white text-decoration-none mx-2 my-3 fs-2 hover-icon hover-bg" title="Hoteles">
            <i class="bi bi-houses-fill"></i>
        </a>
         <!-- Tarifas -->
         <a href="{{ route('admin.prices.index') }}" class="text-white text-decoration-none mx-2 my-3 fs-2 hover-icon hover-bg" title="Tarifas">
            <i class="bi bi-wallet-fill"></i>
        </a>

    <div class="mt-auto">
        <button class="btn btn-transparent text-danger fs-6" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="bi bi-box-arrow-left"></i>
            {{ Auth::guard('admins')->user()->usuario ?? 'No identificado' }}
        </button>
        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</div>
