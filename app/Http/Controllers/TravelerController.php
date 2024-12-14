<?php

namespace App\Http\Controllers;

use App\Models\Traveler;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TravelerController extends Controller
{
    /**
     * Mostrar formulario de registro.
     */
    public function showRegistrationForm()
    {
        return view('travelers.register');
    }

    /**
     * Registrar un nuevo viajero.
     */
    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido1' => 'required|string|max:255',
            'email' => 'required|email|unique:transfer_viajeros,email', // Campo en minúscula
            'password' => 'required|min:6|confirmed',
        ]);

        Traveler::create([
            'nombre' => $request->nombre,
            'apellido1' => $request->apellido1,
            'apellido2' => $request->apellido2 ?? '', // Valor predeterminado vacío
            'direccion' => $request->direccion ?? '',
            'codigo_postal' => $request->codigo_postal ?? '',
            'ciudad' => $request->ciudad ?? '',
            'pais' => $request->pais ?? '',
            'email' => $request->email,
            'password' => $request->password, // La contraseña se hashea automáticamente en el modelo
        ]);

        return redirect()->route('traveler.login')->with('success', 'Registro exitoso. Por favor, inicia sesión.');
    }

    /**
     * Mostrar formulario de login.
     */
    public function showLoginForm()
    {
        return view('travelers.login');
    }

    /**
     * Manejar login de viajero.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email', // Campo en minúscula
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password'); // Credenciales en minúscula

        if (Auth::guard('travelers')->attempt($credentials)) {
            return redirect()->route('traveler.dashboard')->with('success', 'Inicio de sesión exitoso.');
        }

        return back()->withErrors(['email' => 'Credenciales incorrectas.']);
    }

    /**
     * Mostrar perfil del viajero.
     */
    public function show($id)
    {
        $traveler = Traveler::findOrFail($id);

        return view('travelers.profile', compact('traveler'));
    }

    /**
     * Actualizar perfil del viajero.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'nullable|string|max:255',
            'apellido1' => 'nullable|string|max:255',
            'apellido2' => 'nullable|string|max:255',
            'direccion' => 'nullable|string|max:255',
            'codigo_postal' => 'nullable|string|max:20',
            'ciudad' => 'nullable|string|max:255',
            'pais' => 'nullable|string|max:255',
            'email' => "nullable|email|unique:transfer_viajeros,email,$id,id_viajero",
            'password' => 'nullable|min:6|confirmed',
        ]);

        $traveler = Traveler::findOrFail($id);

        $data = $request->only([
            'nombre',
            'apellido1',
            'apellido2',
            'direccion',
            'codigo_postal',
            'ciudad',
            'pais',
            'email'
        ]);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $traveler->update($data);

        return back()->with('success', 'Perfil actualizado correctamente.');
    }


    public function dashboard()
    {
        $traveler = Auth::guard('travelers')->user();
        $bookings = Booking::where('email_cliente', $traveler->email)->get();
        return view('travelers.dashboard', compact('bookings', 'traveler'));
    }

    /**
     * Cerrar sesión del viajero.
     */
    public function logout()
    {
        Auth::guard('travelers')->logout();
        Auth::guard('travelers')->logout();

        return redirect()->route('traveler.login')->with('success', 'Sesión cerrada correctamente.');
    }




    public function getCalendarEvents(Request $request)
    {
        try {
            $traveler = Auth::guard('travelers')->user();
            $bookings = Booking::where('email_cliente', $traveler->email)->get();

            // Log para verificar las reservas obtenidas
            Log::info('Reservas obtenidas:', ['bookings' => $bookings]);

            // Formatear los eventos para FullCalendar
            $events = $bookings->map(function ($booking) {
                $startDate = $booking->id_tipo_reserva == 1 ? $booking->fecha_entrada : $booking->fecha_vuelo_salida;
                $startTime = $booking->id_tipo_reserva == 1 ? $booking->hora_entrada : $booking->hora_vuelo_salida;
                $start = $startDate && $startTime ? "$startDate $startTime" : $startDate;

                return [
                    'id' => $booking->id_reserva,
                    'title' => 'Hotel ' . $booking->id_hotel,
                    'start' => $start,
                    'extendedProps' => [
                        'id_hotel' => $booking->id_hotel,
                        'id_destino' => $booking->id_destino,
                        'localizador' => $booking->localizador,
                        'id_tipo_reserva' => $booking->id_tipo_reserva,
                        'email_cliente' => $booking->email_cliente,
                        'fecha_reserva' => $booking->fecha_reserva,
                        'fecha_modificacion' => $booking->fecha_modificacion,
                        'hora_entrada' => $booking->hora_entrada,
                        'numero_vuelo_entrada' => $booking->numero_vuelo_entrada,
                        'origen_vuelo_entrada' => $booking->origen_vuelo_entrada,
                        'hora_vuelo_salida' => $booking->hora_vuelo_salida,
                        'num_viajeros' => $booking->num_viajeros,
                        'id_vehiculo' => $booking->id_vehiculo,
                        'tipo_creador_reserva' => $booking->tipo_creador_reserva,
                    ],
                ];
            });

            Log::info('Eventos generados correctamente para el calendario.');

            return response()->json($events);
        } catch (\Exception $e) {
            Log::error('Error al cargar eventos para el calendario:', ['exception' => $e->getMessage()]);
            return response()->json(['error' => 'No se pudieron cargar los eventos'], 500);
        }
    }



}
