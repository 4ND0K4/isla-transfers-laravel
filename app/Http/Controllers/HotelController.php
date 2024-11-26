<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str; //para el metodo de usuario unico
use Illuminate\Support\Facades\DB; // Importar la clase DB
use Illuminate\Support\Facades\Log;



class HotelController extends Controller
{
    public function index()
    {
        $hotels = Hotel::all();
        return view('admin.hotels.index', compact('hotels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_zona' => 'required|integer',
            'comision' => 'required|numeric',
            'password' => 'required|string',
        ]);

        // Generar un usuario único
        do {
            $usuario = 'h' . substr(str_shuffle('0123456789'), 0, 8);
        } while (Hotel::where('usuario', $usuario)->exists());

        // Crear el hotel
        Hotel::create([
            'id_zona' => $request->id_zona,
            'comision' => $request->comision,
            'usuario' => $usuario,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.hotels.index')->with('success', 'Hotel creado correctamente.');
    }

     /**
     * Manejar login de hotel.
     */

     public function showLoginForm()
{
    return view('hotels.login'); // Asegúrate de que esta vista exista
}
    public function login(Request $request)
{
    $request->validate([
        'usuario' => 'required|string',
        'password' => 'required',
    ]);

    $hotel = Hotel::where('usuario', $request->usuario)->first();

    if ($hotel && Hash::check($request->password, $hotel->password)) {
        Auth::guard('hotels')->login($hotel);
        return redirect()->route('hotel.dashboard')->with('success', 'Inicio de sesión exitoso.');
    }

    return back()->withErrors(['usuario' => 'Credenciales incorrectas.']);
}


public function dashboard()
{
    // Recuperar el hotel autenticado
    $hotel = Auth::guard('hotels')->user();

    // Calcular las comisiones agrupadas por año y mes
    $comisiones = Booking::join('transfer_precios', function ($join) {
        $join->on('transfer_reservas.id_hotel', '=', 'transfer_precios.id_hotel')
             ->on('transfer_reservas.id_vehiculo', '=', 'transfer_precios.id_vehiculo');
    })
    ->where('transfer_reservas.id_hotel', $hotel->id_hotel)
    ->selectRaw("
        YEAR(CASE
            WHEN transfer_reservas.id_tipo_reserva = 1 THEN transfer_reservas.fecha_entrada
            WHEN transfer_reservas.id_tipo_reserva = 2 THEN transfer_reservas.fecha_vuelo_salida
        END) as year,
        MONTH(CASE
            WHEN transfer_reservas.id_tipo_reserva = 1 THEN transfer_reservas.fecha_entrada
            WHEN transfer_reservas.id_tipo_reserva = 2 THEN transfer_reservas.fecha_vuelo_salida
        END) as month,
        SUM(transfer_precios.precio * (? / 100)) as total_comision",
        [$hotel->comision]
    )
    ->groupBy('year', 'month')
    ->orderByDesc('year')
    ->orderByDesc('month')
    ->get();


    // Retornar la vista del dashboard con las comisiones
    return view('hotels.dashboard', compact('comisiones', 'hotel'));
}





    public function update(Request $request, Hotel $hotel)
    {
        $request->validate([
            'id_zona' => 'required|integer',
            'comision' => 'required|numeric',
        ]);

        $data = $request->only(['id_zona', 'comision']);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $hotel->update($data);

        return redirect()->route('admin.hotels.index')->with('success', 'Hotel actualizado correctamente.');
    }

    public function destroy(Hotel $hotel)
    {
        $hotel->delete();

        return redirect()->route('admin.hotels.index')->with('success', 'Hotel eliminado correctamente.');
    }


    //Para un solo hotel
    public function comisionesMensuales($hotelId)
{
    $hotel = Hotel::findOrFail($hotelId); // Verificar que el hotel exista

    // Obtener reservas agrupadas por año y mes
    $comisiones = Booking::where('id_hotel', $hotelId)
        ->selectRaw("
            YEAR(CASE
                WHEN id_tipo_reserva = 1 THEN fecha_entrada
                WHEN id_tipo_reserva = 2 THEN fecha_vuelo_salida
            END) as year,
            MONTH(CASE
                WHEN id_tipo_reserva = 1 THEN fecha_entrada
                WHEN id_tipo_reserva = 2 THEN fecha_vuelo_salida
            END) as month,
            SUM(precio * (? / 100)) as total_comision",
            [$hotel->comision]
        )
        ->groupBy('year', 'month')
        ->orderByDesc('year')
        ->orderByDesc('month')
        ->get();

    return view('hotels.comisiones', compact('comisiones', 'hotel'));
}

    //Para todos los hoteles
    public function comisionesPorHoteles()
    {
        // Obtener comisiones agrupadas por hotel, año y mes
        $comisiones = Booking::selectRaw("
                id_hotel,
                YEAR(CASE
                    WHEN id_tipo_reserva = 1 THEN fecha_entrada
                    WHEN id_tipo_reserva = 2 THEN fecha_vuelo_salida
                END) as year,
                MONTH(CASE
                    WHEN id_tipo_reserva = 1 THEN fecha_entrada
                    WHEN id_tipo_reserva = 2 THEN fecha_vuelo_salida
                END) as month,
                SUM(precio * (transfer_hotel.comision / 100)) as total_comision"
            )
            ->join('transfer_hotel', 'transfer_reservas.id_hotel', '=', 'transfer_hotel.id_hotel')
            ->groupBy('id_hotel', 'year', 'month')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->get();

        return view('admin.hotels.comisiones', compact('comisiones'));
    }


//Grafico de comisiones por mes hotel dashboard
public function compararMesActualAnterior($hotelId)
{

    // Verificar que el hotel exista
    $hotel = Hotel::findOrFail($hotelId);

    // Fechas para el mes anterior y el mes actual
    $inicioMesAnterior = now()->subMonth()->startOfMonth();
    $finMesAnterior = now()->subMonth()->endOfMonth();
    $inicioMesActual = now()->startOfMonth();
    $finMesActual = now();

    Log::info("Mes Anterior: {$inicioMesAnterior} - {$finMesAnterior}");
    Log::info("Mes Actual: {$inicioMesActual} - {$finMesActual}");

    // Obtener reservas del mes anterior y actual
    $reservas = Booking::where('transfer_reservas.id_hotel', $hotelId) // Prefijo agregado
    ->where(function ($query) use ($inicioMesAnterior, $finMesActual) {
        $query->where(function ($q) use ($inicioMesAnterior, $finMesActual) {
            $q->whereBetween('fecha_entrada', [$inicioMesAnterior, $finMesActual])
              ->where('id_tipo_reserva', 1);
        })->orWhere(function ($q) use ($inicioMesAnterior, $finMesActual) {
            $q->whereBetween('fecha_vuelo_salida', [$inicioMesAnterior, $finMesActual])
              ->where('id_tipo_reserva', 2);
        });
    })
    ->join('transfer_precios', function ($join) {
        $join->on('transfer_reservas.id_hotel', '=', 'transfer_precios.id_hotel')
             ->on('transfer_reservas.id_vehiculo', '=', 'transfer_precios.id_vehiculo');
    })
    ->select('transfer_reservas.*', 'transfer_precios.precio') // Incluye el precio
    ->get();


dd($reservas->toArray());


Log::info('Reservas encontradas:', $reservas->toArray());


    // Agrupar reservas por mes y calcular comisiones
    $comisiones = $reservas->groupBy(function ($reserva) {
        if ($reserva->id_tipo_reserva === 1) {
            return \Carbon\Carbon::parse($reserva->fecha_entrada)->format('m');
        } elseif ($reserva->id_tipo_reserva === 2) {
            return \Carbon\Carbon::parse($reserva->fecha_vuelo_salida)->format('m');
        }
    })->map(function ($reservasDelMes) use ($hotel) {
        return $reservasDelMes->sum(function ($reserva) use ($hotel) {
            return $reserva->precio->precio * ($hotel->comision / 100); // Asume que `precio` es accesible desde la relación
        });
    });


    Log::info('Comisiones por mes:', $comisiones->toArray());


    // Preparar etiquetas y datos para el gráfico
    $labels = ['Mes Anterior', 'Mes Actual'];
    $data = [
        $comisiones[$inicioMesAnterior->format('m')] ?? 0,
        $comisiones[$inicioMesActual->format('m')] ?? 0,
    ];

    Log::info('Labels:', $labels);
    Log::info('Data:', $data);


    return view('hotels.dashboard', compact('labels', 'data', 'hotel'));
}



}
