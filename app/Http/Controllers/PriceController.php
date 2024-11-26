<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Price;
use App\Models\Hotel;
use App\Models\Vehicle;

class PriceController extends Controller
{
    public function index()
    {
        $prices = Price::with(['hotel', 'vehicle'])->get(); // Obtener precios con relaciones
        $hotels = Hotel::all();
        $vehicles = Vehicle::all();

        return view('admin.prices.index', compact('prices', 'hotels', 'vehicles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_hotel' => 'required|exists:transfer_hotel,id_hotel',
            'id_vehiculo' => 'required|exists:transfer_vehiculo,id_vehiculo',
            'precio' => 'required|numeric|min:0',
        ]);

        Price::create($request->only(['id_hotel', 'id_vehiculo', 'precio']));

        return redirect()->route('admin.prices.index')->with('success', 'Precio creado correctamente.');
    }

    public function destroy(Price $price)
    {
        $price->delete();

        return redirect()->route('admin.prices.index')->with('success', 'Precio eliminado correctamente.');
    }

    // Método para obtener el precio de un hotel y un vehículo
    public function obtenerPrecio($id_hotel, $id_vehiculo)
    {
        // Verificar si existe un precio para el hotel y vehículo
        $precio = TransferPrecio::where('id_hotel', $id_hotel)
            ->where('id_vehiculo', $id_vehiculo)
            ->value('precio');

        if ($precio === null) {
            return response()->json(['error' => 'Precio no encontrado'], 404);
        }

        return response()->json(['precio' => $precio]);
    }

}
