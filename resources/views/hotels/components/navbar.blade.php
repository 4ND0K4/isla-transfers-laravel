<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand caveat-brush-regular text-success fs-3" href="{{ route('hotel.dashboard') }}">
                <img src="{{ asset('images/icons/logo_app.png') }}" alt="Ícono" width="55" height="45">
                Isla Transfers
            </a>
        </div>
        <ul class="nav navbar-nav">
            <li>
                <a href="{{ route('hotel.bookings.index') }}" class="btn btn-outline-success fw-bold ms-2">
                    Transfers
                </a>
            </li>
            <li>
                <a href="{{ route('hotel.commissions.index') }}" class="btn btn-outline-success fw-bold ms-2">
                    Comisiones
                </a>
            </li>
            <li>
                <a href="{{ route('hotel.trips.index') }}" class="btn btn-outline-success fw-bold ms-2">
                    Excursiones
                </a>
            </li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li class="nav-item mt-1">
            @if(Auth::guard('hotels')->check())
                    <a class="text-center fs-5 text-decoration-none"><span class="text-dark">Bienvenido,</span> <span class="text-warning">{{ Auth::guard('hotels')->user()->usuario }}</span></a>
            @endif
            </li>
            <li class="nav-item">
                <form id="logout-form" action="{{ route('hotel.logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
                <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fa-solid fa-right-from-bracket text-danger"></i>
                </a>
            </li>
        </ul>
    </div>
</nav>

