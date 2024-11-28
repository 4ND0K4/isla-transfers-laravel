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
            'codigoPostal' => $request->codigoPostal ?? '',
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
            'codigoPostal' => 'nullable|string|max:20',
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
            'codigoPostal',
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
        $traveler = Auth::user(); // Obtiene el usuario autenticado
        return view('travelers.dashboard', compact('traveler'));
    }

    /**
     * Cerrar sesión del viajero.
     */
    public function logout()
    {
        Auth::guard('travelers')->logout();

        return redirect()->route('traveler.login')->with('success', 'Sesión cerrada correctamente.');
    }

    public function storeBooking(Request $request)
    {
        Log::info('Inicio del método storeBooking para crear una reserva.');

        $traveler = Auth::guard('travelers')->user();
        $tipoCreadorReserva = 2; // Traveler

        // Validar los datos de entrada
        try {
            $validated = $request->validate([
                'id_tipo_reserva' => 'required|in:1,2,idayvuelta',
                'id_destino' => 'required|exists:transfer_hotel,id_hotel',
                'num_viajeros' => 'required|integer|min:1',
                'fecha_entrada' => 'nullable|date|required_if:id_tipo_reserva,1,idayvuelta',
                'hora_entrada' => 'nullable|required_if:id_tipo_reserva,1,idayvuelta',
                'fecha_vuelo_salida' => 'nullable|date|required_if:id_tipo_reserva,2,idayvuelta',
                'hora_vuelo_salida' => 'nullable|required_if:id_tipo_reserva,2,idayvuelta',
                'numero_vuelo_entrada' => 'nullable|string',
                'origen_vuelo_entrada' => 'nullable|string',
                'id_vehiculo' => 'nullable|integer|exists:transfer_vehiculo,id_vehiculo',
            ]);

            Log::info('Datos validados correctamente:', $validated);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Error de validación:', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors());
        }

        // Restricción para viajeros: no pueden crear reservas con menos de 48 horas de antelación
        $fechaMinima = Carbon::now()->addDays(2);
        Log::info("Restricción de 48 horas: la fecha mínima permitida es {$fechaMinima}");

        if (
            (isset($validated['fecha_entrada']) && Carbon::parse($validated['fecha_entrada'])->lt($fechaMinima)) ||
            (isset($validated['fecha_vuelo_salida']) && Carbon::parse($validated['fecha_vuelo_salida'])->lt($fechaMinima))
        ) {
            Log::warning('El viajero intentó crear una reserva con menos de 48 horas de antelación.');
            return redirect()->back()->withErrors([
                'error' => 'No puede realizar reservas con menos de 48 horas de antelación.',
            ]);
        }

        // Preparar datos comunes para las reservas, asignando valores predeterminados
        $baseData = array_merge($validated, [
            'fecha_reserva' => now(),
            'fecha_modificacion' => now(),
            'tipo_creador_reserva' => $tipoCreadorReserva,
            'email_cliente' => $traveler->email,
            'fecha_entrada' => $validated['fecha_entrada'] ?? '1970-01-01',
            'hora_entrada' => $validated['hora_entrada'] ?? '00:00:00',
            'fecha_vuelo_salida' => $validated['fecha_vuelo_salida'] ?? '1970-01-01',
            'hora_vuelo_salida' => $validated['hora_vuelo_salida'] ?? '00:00:00',
            'numero_vuelo_entrada' => $validated['numero_vuelo_entrada'] ?? '',
            'origen_vuelo_entrada' => $validated['origen_vuelo_entrada'] ?? ''
        ]);
        Log::info('Datos base para la reserva después de procesar valores predeterminados:', $baseData);
        // Asignar el valor de id_destino a id_hotel
        $baseData['id_hotel'] = $validated['id_destino'];
        Log::info('Asignando id_destino a id_hotel:', ['id_hotel' => $baseData['id_hotel']]);
        // Crear las reservas
        try {
            if ($validated['id_tipo_reserva'] === 'idayvuelta') {
                Log::info('Creando reservas de tipo ida y vuelta.');

                // Reserva 1: Aeropuerto -> Hotel
                $firstBookingData = array_merge($baseData, [
                    'id_tipo_reserva' => 1,
                    'fecha_vuelo_salida' => '1970-01-01', // Fecha vacía para este tipo de reserva
                    'hora_vuelo_salida' => '00:00:00',    // Hora vacía para este tipo de reserva
                ]);
                $firstBooking = Booking::create($firstBookingData);
                Log::info('Primera reserva creada con éxito:', $firstBooking->toArray());

                // Reserva 2: Hotel -> Aeropuerto
                $secondBookingData = array_merge($baseData, [
                    'id_tipo_reserva' => 2,
                    'fecha_entrada' => '1970-01-01', // Fecha vacía para este tipo de reserva
                    'hora_entrada' => '00:00:00',    // Hora vacía para este tipo de reserva
                    'numero_vuelo_entrada' => '',   // Número de vuelo vacío para este tipo de reserva
                    'origen_vuelo_entrada' => '',   // Origen de vuelo vacío para este tipo de reserva
                ]);
                $secondBooking = Booking::create($secondBookingData);
                Log::info('Segunda reserva creada con éxito:', $secondBooking->toArray());

            } else {
                Log::info('Creando una reserva única.');

                // Ajustar datos según el tipo de reserva
                if ($validated['id_tipo_reserva'] == 1) {
                    $baseData['fecha_vuelo_salida'] = '1970-01-01';
                    $baseData['hora_vuelo_salida'] = '00:00:00';
                } elseif ($validated['id_tipo_reserva'] == 2) {
                    $baseData['fecha_entrada'] = '1970-01-01';
                    $baseData['hora_entrada'] = '00:00:00';
                    $baseData['numero_vuelo_entrada'] = '';
                    $baseData['origen_vuelo_entrada'] = '';
                }

                $baseData['id_hotel'] = $validated['id_destino'];
                $booking = Booking::create($baseData);
                Log::info('Reserva única creada con éxito:', $booking->toArray());
            }

            return redirect()->route('traveler.bookings.index')->with('success', 'Reserva creada correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al crear la reserva:', ['message' => $e->getMessage()]);
            return redirect()->back()->withErrors(['error' => 'Hubo un error al crear la reserva.']);
        }
    }

    public function index(Request $request)
    {
        $traveler = Auth::guard('travelers')->user();
        $id_tipo_reserva = $request->get('id_tipo_reserva');

        $bookings = Booking::when($id_tipo_reserva, function ($query, $id_tipo_reserva) {
            return $query->where('id_tipo_reserva', $id_tipo_reserva);
        })->where('email_cliente', $traveler->email)->get();

        return view('travelers.bookings.index', compact('bookings', 'id_tipo_reserva'));
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

    public function deleteBooking($id)
    {
        try {
            // Buscar la reserva por ID
            $booking = Booking::findOrFail($id);

            // Aplicar la restricción de 48 horas para viajeros
            $fechaMinima = now()->addDays(2);

            // Validar si la reserva está dentro del período restringido de 48 horas
            if (
                ($booking->id_tipo_reserva == 1 && $booking->fecha_entrada < $fechaMinima) ||
                ($booking->id_tipo_reserva == 2 && $booking->fecha_vuelo_salida < $fechaMinima)
            ) {
                return redirect()->back()->withErrors(['error' => 'No puede eliminar reservas con menos de 48 horas de antelación.']);
            }

            // Intentar eliminar la reserva
            $booking->delete();
            Log::info("Reserva eliminada correctamente. ID: {$id}");

            return redirect()->route('traveler.bookings.index')->with('success', 'Reserva eliminada correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al eliminar la reserva con ID: {$id}. Mensaje: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withErrors(['error' => 'Hubo un error al intentar eliminar la reserva.']);
        }
    }

    public function updateBooking(Request $request, $id)
    {
        try {
            Log::info("Intentando actualizar la reserva con ID recibido: {$id}");

            // Buscar la reserva a actualizar
            $booking = Booking::findOrFail($id);
            Log::info('Reserva encontrada:', $booking->toArray());

            // Validar los datos recibidos
            $validated = $request->validate([
                'id_tipo_reserva' => 'required|in:1,2',
                'id_destino' => 'required|exists:transfer_hotel,id_hotel',
                'num_viajeros' => 'required|integer|min:1',
                'fecha_entrada' => 'nullable|date|required_if:id_tipo_reserva,1',
                'hora_entrada' => 'nullable|required_if:id_tipo_reserva,1',
                'fecha_vuelo_salida' => 'nullable|date|required_if:id_tipo_reserva,2',
                'hora_vuelo_salida' => 'nullable|required_if:id_tipo_reserva,2',
                'numero_vuelo_entrada' => 'nullable|string',
                'origen_vuelo_entrada' => 'nullable|string',
                'id_vehiculo' => 'nullable|integer|exists:transfer_vehiculo,id_vehiculo',
            ]);

            Log::info('Datos validados correctamente:', $validated);

            // Preparar datos para la actualización
            $updateData = $validated;

            // Asignar id_destino al campo id_hotel
            $updateData['id_hotel'] = $validated['id_destino'];
            Log::info('Asignando id_destino a id_hotel:', ['id_hotel' => $updateData['id_hotel']]);

            // Limpiar campos irrelevantes según el tipo de reserva
            if ($validated['id_tipo_reserva'] == 1) {
                $updateData['fecha_vuelo_salida'] = '1970-01-01'; // Vaciar
                $updateData['hora_vuelo_salida'] = '00:00:00'; // Vaciar
            } elseif ($validated['id_tipo_reserva'] == 2) {
                $updateData['fecha_entrada'] = '1970-01-01'; // Vaciar
                $updateData['hora_entrada'] = '00:00:00'; // Vaciar
            }

            // Valores predeterminados para campos que no pueden ser NULL
            $updateData['numero_vuelo_entrada'] = $validated['numero_vuelo_entrada'] ?? '';
            $updateData['origen_vuelo_entrada'] = $validated['origen_vuelo_entrada'] ?? '';

            // Registrar modificación
            $updateData['fecha_modificacion'] = now();

            // Actualizar la reserva
            $booking->update($updateData);
            Log::info("Reserva actualizada correctamente. ID: {$id}");

            return redirect()->route('traveler.bookings.index')->with('success', 'Reserva actualizada correctamente.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Error de validación al actualizar la reserva:', $e->errors());
            return redirect()->back()->withErrors($e->errors());
        } catch (\Exception $e) {
            Log::error("Error al actualizar la reserva con ID: {$id}. Mensaje: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withErrors(['error' => 'Hubo un error al intentar actualizar la reserva.']);
        }
    }
}
