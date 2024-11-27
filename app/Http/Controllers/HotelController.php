<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Booking;
use App\Models\Price;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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
            return view('hotels.login');
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
            $hotel = Auth::guard('hotels')->user(); // Obtener el hotel autenticado

            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;
            $previousMonth = Carbon::now()->subMonth()->month;
            $previousMonthYear = Carbon::now()->subMonth()->year;

            // Comisiones del mes actual
            $currentMonthCommissions = Booking::join('transfer_precios', function ($join) {
                $join->on('transfer_reservas.id_hotel', '=', 'transfer_precios.id_hotel')
                     ->on('transfer_reservas.id_vehiculo', '=', 'transfer_precios.id_vehiculo');
            })
            ->join('transfer_hotel', 'transfer_reservas.id_hotel', '=', 'transfer_hotel.id_hotel') // Agregar el join con transfer_hotel
            ->where('transfer_reservas.id_hotel', $hotel->id_hotel)
            ->whereYear('fecha_reserva', $currentYear)
            ->whereMonth('fecha_reserva', $currentMonth)
            ->sum(DB::raw('transfer_precios.precio * (transfer_hotel.comision / 100)'));

            // Comisiones del mes anterior
            $previousMonthCommissions = Booking::join('transfer_precios', function ($join) {
                    $join->on('transfer_reservas.id_hotel', '=', 'transfer_precios.id_hotel')
                         ->on('transfer_reservas.id_vehiculo', '=', 'transfer_precios.id_vehiculo');
                })
                ->join('transfer_hotel', 'transfer_reservas.id_hotel', '=', 'transfer_hotel.id_hotel') // Agregar el join con transfer_hotel
                ->where('transfer_reservas.id_hotel', $hotel->id_hotel)
                ->whereYear('fecha_reserva', $previousMonthYear)
                ->whereMonth('fecha_reserva', $previousMonth)
                ->sum(DB::raw('transfer_precios.precio * (transfer_hotel.comision / 100)'));

            // Comisiones agrupadas por mes y año
            $comisiones = Booking::join('transfer_precios', function ($join) {
                $join->on('transfer_reservas.id_hotel', '=', 'transfer_precios.id_hotel')
                     ->on('transfer_reservas.id_vehiculo', '=', 'transfer_precios.id_vehiculo');
            })
            ->join('transfer_hotel', 'transfer_reservas.id_hotel', '=', 'transfer_hotel.id_hotel')
            ->where('transfer_reservas.id_hotel', $hotel->id_hotel)
            ->selectRaw("
                YEAR(fecha_reserva) as year,
                MONTH(fecha_reserva) as month,
                SUM(transfer_precios.precio * (transfer_hotel.comision / 100)) as total_comision
            ")
            ->groupBy('year', 'month')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->get();

            // Crear el gráfico
            $chart = new \ConsoleTVs\Charts\Classes\Chartjs\Chart();
            $chart->labels(['Mes Anterior', 'Mes Actual']);
            $chart->dataset('Comparación de Comisiones', 'bar', [$previousMonthCommissions, $currentMonthCommissions])
                  ->backgroundColor(['#FF6384', '#36A2EB']);

            return view('hotels.dashboard', compact('chart', 'hotel', 'comisiones'));
        }



/*public function dashboard()
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
*/

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


    //Para un solo hotel desde el panel de administración
    public function comisionesMensuales($hotelId)
    {
        $hotel = Hotel::findOrFail($hotelId);

        $comisiones = DB::table('transfer_reservas')
            ->join('transfer_precios', function ($join) {
                $join->on('transfer_reservas.id_hotel', '=', 'transfer_precios.id_hotel')
                     ->on('transfer_reservas.id_vehiculo', '=', 'transfer_precios.id_vehiculo');
            })
            ->where('transfer_reservas.id_hotel', $hotelId)
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

        // Retornar los datos como JSON
        return response()->json($comisiones);
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

}
