<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TravelerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\TourController;

// Ruta principal
Route::get('/', function () {
    return view('welcome');
});

// *** RUTAS PARA VIAJEROS ***
Route::prefix('traveler')->name('traveler.')->group(function () {
    // Login y Registro
    Route::get('/login', [TravelerController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [TravelerController::class, 'login']);
    Route::post('/logout', [TravelerController::class, 'logout'])->name('logout');
    Route::get('/register', [TravelerController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [TravelerController::class, 'register']);

    // Rutas protegidas
    Route::middleware('auth:travelers')->group(function () {
        Route::get('/dashboard', [TravelerController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile/{id}', [TravelerController::class, 'show'])->name('profile');
        Route::post('/profile/{id}', [TravelerController::class, 'update']);

        // Reservas
        Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
        Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
        Route::put('/bookings/{id}', [BookingController::class, 'update'])->name('bookings.update');
        Route::delete('/bookings/{id}', [BookingController::class, 'destroy'])->name('bookings.destroy');

        // Calendario
        Route::get('/calendar/events', [BookingController::class, 'getCalendarEvents'])->name('calendar.events');
    });
});
Route::resource('travelers', TravelerController::class);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// *** RUTAS PARA ADMINISTRADORES ***
Route::prefix('admin')->name('admin.')->group(function () {
    // Login y Logout
    Route::get('/login', [AdminController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminController::class, 'login']);
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

    // Rutas protegidas
    Route::middleware('auth:admins')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Reservas
        Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
        Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
        Route::put('/bookings/{id}', [BookingController::class, 'update'])->name('bookings.update');
        Route::delete('/bookings/{id}', [BookingController::class, 'destroy'])->name('bookings.destroy');

        // Calendario
        Route::get('/calendar/events', [BookingController::class, 'getCalendarEvents'])->name('calendar.events');

        // Hoteles
        Route::resource('hotels', HotelController::class)->except(['show', 'create', 'edit']);

        // Vehículos
        Route::resource('vehicles', VehicleController::class)->except(['show', 'create', 'edit']);

        // Tours
        Route::resource('tours', TourController::class)->except(['show', 'create', 'edit']);

    });
});

// *** RUTAS PARA HOTELES ***
Route::prefix('hotel')->name('hotel.')->middleware('auth:hotels')->group(function () {
    Route::get('/dashboard', [HotelController::class, 'dashboard'])->name('dashboard');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
});




