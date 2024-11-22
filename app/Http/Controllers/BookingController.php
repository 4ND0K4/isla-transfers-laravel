<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Traveler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{

    public function index(Request $request)
    {
        // Capturar el filtro por tipo de reserva desde la solicitud
        $id_tipo_reserva = $request->get('id_tipo_reserva');

        // Obtener las reservas filtradas por tipo o todas si no hay filtro
        $bookings = Booking::when($id_tipo_reserva, function ($query, $id_tipo_reserva) {
            return $query->where('id_tipo_reserva', $id_tipo_reserva);
        })->get();

        return view('admin.bookings.index', compact('bookings', 'id_tipo_reserva'));
    }

    public function getBooking($id)
    {
        $booking = Booking::with(['hotel', 'vehicle', 'traveler'])->findOrFail($id);
        return response()->json($booking);
    }



    public function store(Request $request)
{
    Log::info('Inicio del método store para crear una reserva.');

    $tipoCreadorReserva = 0; // Por defecto, no identificado
    if (Auth::guard('admins')->check()) {
        $tipoCreadorReserva = 1; // Admin
    } elseif (Auth::guard('travelers')->check()) {
        $tipoCreadorReserva = 2; // Traveler
    } elseif (Auth::guard('hotels')->check()) {
        $tipoCreadorReserva = 3; // Hotel
    }

    if ($tipoCreadorReserva === 0) {
        Log::error('No se pudo determinar el tipo de creador de la reserva. Usuario no autenticado o guard no válido.');
        return redirect()->back()->withErrors(['error' => 'No se puede procesar la reserva. Inicie sesión nuevamente.']);
    }

    Log::info("Tipo de creador de la reserva: {$tipoCreadorReserva} (1 = Admin, 2 = Traveler, 3 = Hotel)");


    // Validar los datos de entrada
    try {
        $validated = $request->validate([
            'id_tipo_reserva' => 'required|in:1,2,idayvuelta',
            'id_destino' => 'required|exists:transfer_hotel,id_hotel',
            'email_cliente' => 'required|email|exists:transfer_viajeros,email',
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
    if ($tipoCreadorReserva === 2) { // Traveler
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
    }

    // Preparar datos comunes para las reservas, asignando valores predeterminados
    $baseData = array_merge($validated, [
        'fecha_reserva' => now(),
        'fecha_modificacion' => now(),
        'tipo_creador_reserva' => $tipoCreadorReserva,
        'fecha_entrada' => $validated['fecha_entrada'] ?? '1970-01-01',
        'hora_entrada' => $validated['hora_entrada'] ?? '00:00:00',
        'fecha_vuelo_salida' => $validated['fecha_vuelo_salida'] ?? '1970-01-01',
        'hora_vuelo_salida' => $validated['hora_vuelo_salida'] ?? '00:00:00',
        'numero_vuelo_entrada' => $validated['numero_vuelo_entrada'] ?? '',
        'origen_vuelo_entrada' => $validated['origen_vuelo_entrada'] ?? ''
    ]);
    Log::info('Datos base para la reserva después de procesar valores predeterminados:', $baseData);

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
            //$this->sendEmailWithLocator($firstBooking);

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
            //$this->sendEmailWithLocator($secondBooking);

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

            $booking = Booking::create($baseData);
            Log::info('Reserva única creada con éxito:', $booking->toArray());
            //$this->sendEmailWithLocator($booking);
        }

        return redirect()->route('admin.bookings.index')->with('success', 'Reserva creada correctamente.');
    } catch (\Exception $e) {
        Log::error('Error al crear la reserva:', ['message' => $e->getMessage()]);
        return redirect()->back()->withErrors(['error' => 'Hubo un error al crear la reserva.']);
    }


    Log::info('Reserva(s) creada(s) correctamente. Redirigiendo al índice de reservas.');
    if ($tipoCreadorReserva === 1) { // Admin
        return redirect()->route('admin.bookings.index')->with('success', 'Reserva creada correctamente.');
    } elseif ($tipoCreadorReserva === 2) { // Traveler
        return redirect()->route('traveler.dashboard')->with('success', 'Reserva creada correctamente.');
    } elseif ($tipoCreadorReserva === 3) { // Hotel
        return redirect()->route('hotel.dashboard')->with('success', 'Reserva creada correctamente.');
    } else {
        return redirect()->back()->withErrors(['error' => 'Hubo un problema al redirigir tras la creación de la reserva.']);
    }

}

/**
 * Enviar un correo electrónico con el localizador de la reserva.
 */
private function sendEmailWithLocator(Booking $booking)
{
    $emailData = [
        'localizador' => $booking->localizador,
        'tipo_reserva' => $booking->id_tipo_reserva === 1 ? 'Aeropuerto - Hotel' : 'Hotel - Aeropuerto',
        'fecha' => $booking->id_tipo_reserva === 1 ? $booking->fecha_entrada : $booking->fecha_vuelo_salida,
        'hora' => $booking->id_tipo_reserva === 1 ? $booking->hora_entrada : $booking->hora_vuelo_salida,
        'hotel' => $booking->id_hotel,
        'origen_vuelo' => $booking->origen_vuelo_entrada,
        'numero_vuelo' => $booking->numero_vuelo_entrada,
        'num_viajeros' => $booking->num_viajeros,
    ];

    Mail::send('admin.email.booking_confirmation', $emailData, function ($message) use ($booking) {
        $message->to($booking->email_cliente)
            ->subject('Confirmación de Reserva')
            ->from('reservas@islatransfer.com', 'Isla Transfer');
    });

    Log::info('Correo electrónico enviado con éxito al cliente:', ['email' => $booking->email_cliente]);
}

public function update(Request $request, $id)
{
    // Intentar realizar la actualización
    try {
        Log::info("Intentando actualizar la reserva con ID: {$id}");

        // Buscar la reserva a actualizar
        $booking = Booking::findOrFail($id);
        Log::info('Reserva encontrada:', $booking->toArray());

        // Validar los datos recibidos
        $validated = $request->validate([
            'id_tipo_reserva' => 'required|in:1,2',
            'id_destino' => 'required|exists:transfer_hotel,id_hotel',
            'email_cliente' => 'required|email|exists:transfer_viajeros,email',
            'num_viajeros' => 'required|integer|min:1',
            'fecha_entrada' => 'nullable|date|required_if:id_tipo_reserva,1',
            'hora_entrada' => 'nullable|required_if:id_tipo_reserva,1',
            'fecha_vuelo_salida' => 'nullable|date|required_if:id_tipo_reserva,2',
            'hora_vuelo_salida' => 'nullable|required_if:id_tipo_reserva,2',
            'numero_vuelo_entrada' => 'nullable|string|max:255',
            'origen_vuelo_entrada' => 'nullable|string|max:255',
        ]);



        Log::info('Datos validados correctamente:', $validated);

        // Restricción de 48 horas para travelers
        if (Auth::guard('travelers')->check()) {
            $fechaMinima = Carbon::now()->addDays(2);

            if (
                ($validated['id_tipo_reserva'] == 1 && isset($validated['fecha_entrada']) && Carbon::parse($validated['fecha_entrada'])->lt($fechaMinima)) ||
                ($validated['id_tipo_reserva'] == 2 && isset($validated['fecha_vuelo_salida']) && Carbon::parse($validated['fecha_vuelo_salida'])->lt($fechaMinima))
            ) {
                Log::warning("Intento fallido de modificación por traveler. Restricción de 48 horas violada para reserva ID: {$id}");
                return redirect()->back()->withErrors(['error' => 'No puede modificar reservas con menos de 48 horas de antelación.']);
            }
        }

        // Actualizar los datos de la reserva
        $booking->update([
            'id_tipo_reserva' => $validated['id_tipo_reserva'],
            'id_destino' => $validated['id_destino'],
            'email_cliente' => $validated['email_cliente'],
            'num_viajeros' => $validated['num_viajeros'],
            'fecha_modificacion' => now(),
            'fecha_entrada' => $validated['fecha_entrada'] ?? '1970-01-01', // Valor por defecto
            'hora_entrada' => $validated['hora_entrada'] ?? '00:00:00', // Valor por defecto
            'fecha_vuelo_salida' => $validated['fecha_vuelo_salida'] ?? '1970-01-01', // Valor por defecto
            'hora_vuelo_salida' => $validated['hora_vuelo_salida'] ?? '00:00:00', // Valor por defecto
            'numero_vuelo_entrada' => $validated['numero_vuelo_entrada'] ?? null,
            'origen_vuelo_entrada' => $validated['origen_vuelo_entrada'] ?? null,
        ]);



        Log::info("Reserva actualizada correctamente. ID: {$id}");

        // Redirigir según el rol del usuario
        if (Auth::guard('admins')->check()) {
            return redirect()->route('admin.bookings.index')->with('success', 'Reserva actualizada correctamente.');
        } elseif (Auth::guard('travelers')->check()) {
            return redirect()->route('traveler.dashboard')->with('success', 'Reserva actualizada correctamente.');
        } elseif (Auth::guard('hotels')->check()) {
            return redirect()->route('hotel.dashboard')->with('success', 'Reserva actualizada correctamente.');
        }

        Log::warning('No se pudo determinar el rol del usuario.');
        return redirect()->back()->withErrors(['error' => 'No se pudo determinar el rol del usuario.']);

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

    public function destroy($id)
    {
        // Buscar la reserva por ID
        $booking = Booking::findOrFail($id);

        // Si el usuario es de tipo traveler, aplicar la restricción de 48 horas
        if (Auth::user()->role === 'traveler') {
            $fechaMinima = now()->addDays(2);

            // Validar si la reserva está dentro del período restringido de 48 horas
            if (
                ($booking->id_tipo_reserva == 1 && $booking->fecha_entrada < $fechaMinima) ||
                ($booking->id_tipo_reserva == 2 && $booking->fecha_vuelo_salida < $fechaMinima)
            ) {
                return redirect()->back()->withErrors(['error' => 'No puede eliminar reservas con menos de 48 horas de antelación.']);
            }
        }

        // Intentar eliminar la reserva
        if ($booking->delete()) {
            return redirect()->route('admin.bookings.index')->with('success', 'Reserva eliminada correctamente.');
        } else {
            return redirect()->back()->withErrors(['error' => 'Error al eliminar la reserva.']);
        }
    }
}
