<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Models\Hotel;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class TourController extends Controller
{
    public function index()
    {
        if (Auth::guard('hotels')->check()) {
            $hotel = Auth::guard('hotels')->user(); // Obtener el hotel autenticado
            $tours = Tour::with('vehicle')->where('id_hotel', $hotel->id_hotel)->get(); // Obtener tours del hotel
            return view('hotels.trips.index', compact('tours'));
        } else {
            $tours = Tour::with('hotel', 'vehicle')->get(); // Relacionamos con hotel y vehículo
            $hotels = Hotel::all();
            $vehicles = Vehicle::all();
            return view('admin.tours.index', compact('tours', 'hotels', 'vehicles'));
        }
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'fecha_excursion' => 'required|date',
            'hora_entrada_excursion' => 'required|date_format:H:i',
            'hora_salida_excursion' => 'required|date_format:H:i',
            'descripcion' => 'required|string|max:255',
            'num_excursionistas' => 'required|integer',
            'email_cliente' => 'required|email|exists:transfer_viajeros,email',
            'id_hotel' => 'required|exists:transfer_hotel,id_hotel',
            'id_vehiculo' => 'nullable|exists:transfer_vehiculo,id_vehiculo',
        ]);

        Tour::create($validatedData);

        return redirect()->route('admin.tours.index')->with('success', 'Excursión creada correctamente.');
    }



    public function update(Request $request, Tour $tour)
    {
        // Registrar que se llamó al método
        Log::info('Método update llamado', ['tour_id' => $tour->id_excursion]);

        try {
            // Validar los datos de entrada
            $validatedData = $request->validate([
                'fecha_excursion' => 'required|date',
                'hora_entrada_excursion' => 'required|date_format:H:i:s', // Aceptar formato con segundos
                'hora_salida_excursion' => 'required|date_format:H:i:s',
                'descripcion' => 'required|string|max:255',
                'num_excursionistas' => 'required|integer',
                'email_cliente' => 'required|email|exists:transfer_viajeros,email',
                'id_hotel' => 'required|exists:transfer_hotel,id_hotel',
                'id_vehiculo' => 'nullable|exists:transfer_vehiculo,id_vehiculo',
            ]);


            // Registrar los datos validados
            Log::info('Datos validados para la actualización', $validatedData);

            // Registrar los datos originales antes de la actualización
            Log::info('Datos originales del tour antes de actualizar', $tour->toArray());

            // Actualizar el registro
            $tour->update($validatedData);
            Log::info('Tour actualizado correctamente', ['tour_id' => $tour->id_excursion]);

            return redirect()->route('admin.tours.index')->with('success', 'Excursión actualizada correctamente.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Captura los errores de validación
            Log::error('Errores de validación', [
                'errors' => $e->errors(),
                'inputs' => $request->all(),
            ]);

            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            // Captura cualquier otro error
            Log::error('Error al actualizar el tour', [
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Error al actualizar la excursión. Intente nuevamente.');
        }
    }



    public function destroy(Tour $tour)
    {
        $tour->delete();

        return redirect()->route('admin.tours.index')->with('success', 'Excursión eliminada correctamente.');
    }
}
