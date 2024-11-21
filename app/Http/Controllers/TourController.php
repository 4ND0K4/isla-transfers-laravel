<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Models\Hotel;
use App\Models\Vehicle;
use Illuminate\Http\Request;


class TourController extends Controller
{
    public function index()
    {
        $tours = Tour::all();
        $hotels = Hotel::all();
        return view('admin.tours.index', compact('tours', 'hotels'));
    }


    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'fecha_excursion' => 'required|date',
            'hora_entrada_excursion' => 'required|date_format:H:i',
            'hora_salida_excursion' => 'required|date_format:H:i',
            'descripcion' => 'required|string|max:255',
            'num_excursionistas' => 'required|integer',
            'email_cliente' => 'required|email',
            'id_hotel' => 'required|exists:transfer_hotel,id_hotel',
            'id_vehiculo' => 'nullable|exists:transfer_vehiculo,id_vehiculo',
        ]);

        // Registrar los datos validados
        Tour::create($validatedData);



        return redirect()->route('admin.tours.index')->with('success', 'Excursión creada correctamente.');
    }


    public function update(Request $request, Tour $tour)
    {
        $request->validate([
            'fecha_excursion' => 'required|date',
            'hora_entrada_excursion' => 'required|date_format:H:i',
            'hora_salida_excursion' => 'required|date_format:H:i',
            'descripcion' => 'required|string|max:255',
            'num_excursionistas' => 'required|integer',
            'email_cliente' => 'required|email',
            'id_hotel' => 'required|exists:transfer_hotel,id_hotel',
            'id_vehiculo' => 'nullable|exists:transfer_vehiculo,id_vehiculo',
        ]);

        $tour->update($request->all());

        return redirect()->route('admin.tours.index')->with('success', 'Excursión actualizada correctamente.');
    }

    public function destroy(Tour $tour)
    {
        $tour->delete();

        return redirect()->route('admin.tours.index')->with('success', 'Excursión eliminada correctamente.');
    }
}
