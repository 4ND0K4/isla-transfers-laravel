<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{

    private function validateBookingData(Request $request)
    {
        return $request->validate([
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
    }


    private function getTipoCreadorReserva()
    {
        if (Auth::guard('admins')->check()) {
            return 1; // Admin
        } elseif (Auth::guard('travelers')->check()) {
            return 2; // Traveler
        } elseif (Auth::guard('hotels')->check()) {
            return 3; // Hotel
        }
        return null;
    }


    public function index(Request $request)
    {
        // Capturar los filtros desde la solicitud
        $id_tipo_reserva = $request->get('id_tipo_reserva');
        $year = $request->get('year');
        $month = $request->get('month');

        // Obtener las reservas filtradas por tipo, año y mes
        $bookings = Booking::when($id_tipo_reserva, function ($query, $id_tipo_reserva) {
                return $query->where('id_tipo_reserva', $id_tipo_reserva);
            })
            ->when($year, function ($query, $year) {
                return $query->where(function ($query) use ($year) {
                    $query->whereYear('fecha_entrada', $year)
                          ->orWhereYear('fecha_vuelo_salida', $year);
                });
            })
            ->when($month, function ($query, $month) {
                return $query->where(function ($query) use ($month) {
                    $query->whereMonth('fecha_entrada', $month)
                          ->orWhereMonth('fecha_vuelo_salida', $month);
                });
            })
            ->get();

        return view('admin.bookings.index', compact('bookings', 'id_tipo_reserva', 'year', 'month'));
    }


    public function store(Request $request)
    {
        Log::info('Inicio del método storeBooking para crear una reserva.');

        $tipoCreadorReserva = $this->getTipoCreadorReserva();
        if (is_null($tipoCreadorReserva)) {
            return redirect()->back()->withErrors(['error' => 'Usuario no autenticado.']);
        }

        // Restricción para travelers
        if ($tipoCreadorReserva == 2) {
            if ($request->input('id_tipo_reserva') == 1) {
                $fechaEntrada = Carbon::parse($request->input('fecha_entrada'));
                if ($fechaEntrada->diffInDays(Carbon::now()) < 2) {
                    return redirect()->back()->withErrors(['error' => 'Los travelers no pueden crear reservas con menos de 2 días de antelación.']);
                }
            } elseif ($request->input('id_tipo_reserva') == 2) {
                $fechaVueloSalida = Carbon::parse($request->input('fecha_vuelo_salida'));
                if ($fechaVueloSalida->diffInDays(Carbon::now()) < 2) {
                    return redirect()->back()->withErrors(['error' => 'Los travelers no pueden crear reservas con menos de 2 días de antelación.']);
                }
            }
        }

        // Validar los datos de entrada
        try {
            $validated = $this->validateBookingData($request);
            Log::info('Datos validados correctamente:', $validated);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Error de validación:', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors());
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

            // Redirigir según el tipo de creador de reserva
            if ($tipoCreadorReserva == 1) {
                return redirect()->route('admin.bookings.index')->with('success', 'Reserva creada correctamente.');
            } elseif ($tipoCreadorReserva == 2) {
                return redirect()->route('traveler.dashboard')->with('success', 'Reserva creada correctamente.');
            } else {
                return redirect()->route('hotel.dashboard')->with('success', 'Reserva creada correctamente.');
            }
        } catch (\Exception $e) {
            Log::error('Error al crear la reserva:', ['message' => $e->getMessage()]);
            return redirect()->back()->withErrors(['error' => 'Hubo un error al crear la reserva.']);
        }
    }


    public function update(Request $request, $id)
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
                'email_cliente' => 'required|email|exists:transfer_viajeros,email',
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

            // Restricción para travelers
            if (Auth::guard('travelers')->check()) {
                if ($validated['id_tipo_reserva'] == 1) {
                    $fechaEntrada = Carbon::parse($validated['fecha_entrada']);
                    if ($fechaEntrada->diffInDays(Carbon::now()) < 2) {
                        return redirect()->back()->withErrors(['error' => 'Los travelers no pueden actualizar reservas con menos de 2 días de antelación.']);
                    }
                } elseif ($validated['id_tipo_reserva'] == 2) {
                    $fechaVueloSalida = Carbon::parse($validated['fecha_vuelo_salida']);
                    if ($fechaVueloSalida->diffInDays(Carbon::now()) < 2) {
                        return redirect()->back()->withErrors(['error' => 'Los travelers no pueden actualizar reservas con menos de 2 días de antelación.']);
                    }
                }
            }

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

            // Redirigir según el tipo de creador de reserva
            if (Auth::guard('admins')->check()) {
                return redirect()->route('admin.bookings.index')->with('success', 'Reserva actualizada correctamente.');
            } elseif (Auth::guard('travelers')->check()) {
                return redirect()->route('traveler.dashboard')->with('success', 'Reserva actualizada correctamente.');
            } else {
                return redirect()->route('hotel.dashboard')->with('success', 'Reserva actualizada correctamente.');
            }
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
        try {
            // Buscar la reserva por ID
            $booking = Booking::findOrFail($id);

            // Intentar eliminar la reserva
            $booking->delete();
            Log::info("Reserva eliminada correctamente. ID: {$id}");

            return redirect()->route('admin.bookings.index')->with('success', 'Reserva eliminada correctamente.');
            //return response()->json(['success' => true, 'message' => 'Reserva eliminada correctamente.']);
        } catch (\Exception $e) {
            Log::error("Error al eliminar la reserva con ID: {$id}. Mensaje: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withErrors(['error' => 'Hubo un error al intentar eliminar la reserva.']);
            //return response()->json(['success' => false, 'message' => 'Hubo un error al intentar eliminar la reserva.']);
        }
    }


    public function getCalendarEvents(Request $request)
    {
        try {
            $user = Auth::guard('admins')->user() ?? Auth::guard('travelers')->user();

            if ($user) {
                if (Auth::guard('admins')->check()) {
                    Log::info('Usuario autenticado como admin:', ['email' => $user->email]);
                    // Si es admin, obtener todas las reservas
                    $bookings = Booking::all();
                } elseif (Auth::guard('travelers')->check()) {
                    Log::info('Usuario autenticado como traveler:', ['email' => $user->email]);
                    // Si es traveler, obtener solo sus reservas
                    $bookings = Booking::where('email_cliente', $user->email)->get();
                }
            } else {
                Log::warning('Usuario no autenticado o no válido.');
                // Si no hay usuario autenticado, devolver error
                return response()->json(['error' => 'Usuario no autenticado'], 401);
            }

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


    public function show($id)
    {
        try {
            $booking = Booking::findOrFail($id);
            return response()->json($booking);
        } catch (\Exception $e) {
            Log::error('Error fetching booking data:', ['exception' => $e->getMessage()]);
            return response()->json(['error' => 'No se pudo obtener la reserva'], 500);
        }
    }


    // Implementa el servicio de recoger rl total de reservas por zona
    public function reservasPorZona()
    {
        // Total de reservas
        $totalReservas = Booking::count();

        // Agrupar reservas por zona
        $reservasPorZona = Booking::selectRaw('transfer_hotel.id_zona, COUNT(*) as total_reservas')
            ->join('transfer_hotel', 'transfer_reservas.id_hotel', '=', 'transfer_hotel.id_hotel')
            ->groupBy('transfer_hotel.id_zona')
            ->get();

        // Calcular el porcentaje y preparar el JSON
        $data = $reservasPorZona->map(function ($item) use ($totalReservas) {
            return [
                'zona' => $item->id_zona == 1 ? 'Norte' : ($item->id_zona == 2 ? 'Sur' : 'Metropolitana'),
                'numero_traslados' => $item->total_reservas,
                'porcentaje' => $totalReservas > 0 ? round(($item->total_reservas / $totalReservas) * 100, 2) : 0,
            ];
        });

        // Retornar datos en JSON
        return response()->json($data);
    }

    // Gráfico basado en la función reservasPorZona en el mes en curso





    public function dashboard(Request $request)
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $id_tipo_reserva = $request->input('id_tipo_reserva');

        // Consultar el número de reservas por zona para el mes y año actuales
        $reservasPorZona = Booking::selectRaw('transfer_hotel.id_zona, COUNT(*) as total_reservas')
            ->join('transfer_hotel', 'transfer_reservas.id_hotel', '=', 'transfer_hotel.id_hotel')
            ->when($id_tipo_reserva, function ($query) use ($currentYear, $currentMonth) {
                if ($id_tipo_reserva == 1) {
                    return $query->whereYear('fecha_entrada', $currentYear)
                                 ->whereMonth('fecha_entrada', $currentMonth);
                } elseif ($id_tipo_reserva == 2) {
                    return $query->whereYear('fecha_vuelo_salida', $currentYear)
                                 ->whereMonth('fecha_vuelo_salida', $currentMonth);
                } else {
                    return $query->where(function ($query) use ($currentYear, $currentMonth) {
                        $query->whereYear('fecha_entrada', $currentYear)
                              ->whereMonth('fecha_entrada', $currentMonth)
                              ->orWhereYear('fecha_vuelo_salida', $currentYear)
                              ->whereMonth('fecha_vuelo_salida', $currentMonth);
                    });
                }
            })
            ->groupBy('transfer_hotel.id_zona')
            ->get();

        // Consultar el número de reservas por hotel para el mes y año actuales
        $reservasPorHotel = Booking::selectRaw('transfer_hotel.id_hotel, COUNT(*) as total_reservas')
            ->join('transfer_hotel', 'transfer_reservas.id_hotel', '=', 'transfer_hotel.id_hotel')
            ->when($id_tipo_reserva, function ($query) use ($currentYear, $currentMonth) {
                if ($id_tipo_reserva == 1) {
                    return $query->whereYear('fecha_entrada', $currentYear)
                                 ->whereMonth('fecha_entrada', $currentMonth);
                } elseif ($id_tipo_reserva == 2) {
                    return $query->whereYear('fecha_vuelo_salida', $currentYear)
                                 ->whereMonth('fecha_vuelo_salida', $currentMonth);
                } else {
                    return $query->where(function ($query) use ($currentYear, $currentMonth) {
                        $query->whereYear('fecha_reserva', $currentYear)
                              ->whereMonth('fecha_reserva', $currentMonth);
                    });
                }
            })
            ->groupBy('transfer_hotel.id_hotel')
            ->get();

        // Preparar etiquetas y valores para el gráfico de zonas
        $labelsZonas = $reservasPorZona->map(function ($item) {
            return $item->id_zona == 1 ? 'Norte' : ($item->id_zona == 2 ? 'Sur' : 'Metropolitana');
        })->toArray();
        $valuesZonas = $reservasPorZona->pluck('total_reservas')->toArray();

        // Preparar etiquetas y valores para el gráfico de hoteles
        $labelsHoteles = $reservasPorHotel->map(function ($item) {
            return 'Hotel ' . $item->id_hotel;
        })->toArray();
        $valuesHoteles = $reservasPorHotel->pluck('total_reservas')->toArray();

        // Crear el gráfico tipo pie
        $chartZonasPie = new Chart();
        $chartZonasPie->labels($labelsZonas);
        $chartZonasPie->dataset('Reservas por Zona', 'pie', $valuesZonas)
                      ->backgroundColor(['#FF6384', '#36A2EB', '#FFCE56']);

        // Crear el gráfico tipo bar para hoteles
        $chartHotelesBar = new Chart();
        $chartHotelesBar->labels($labelsHoteles);
        $chartHotelesBar->dataset('Reservas por Hotel', 'bar', $valuesHoteles)
                        ->backgroundColor('#36A2EB');

        // Retornar los gráficos a la vista
        return view('admin.dashboard', compact('chartZonasPie', 'chartHotelesBar'));
    }


}
