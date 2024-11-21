<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    // Tabla asociada
    protected $table = 'transfer_reservas';

    // Llave primaria
    protected $primaryKey = 'id_reserva';

    // Deshabilitar timestamps (si no usas created_at y updated_at)
    public $timestamps = false;

    // Campos asignables masivamente
    protected $fillable = [
        'localizador',
        'id_hotel',
        'id_tipo_reserva',
        'email_cliente',
        'fecha_reserva',
        'fecha_modificacion',
        'id_destino',
        'fecha_entrada',
        'hora_entrada',
        'numero_vuelo_entrada',
        'origen_vuelo_entrada',
        'hora_vuelo_salida',
        'fecha_vuelo_salida',
        'num_viajeros',
        'id_vehiculo',
        'tipo_creador_reserva',
    ];

    /**
     * Relaciones
     */

    // Relación con la tabla de hoteles
    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'id_hotel');
    }

    // Relación con la tabla de vehículos
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'id_vehiculo');
    }

    // Relación con la tabla de usuarios/travelers
    public function traveler()
    {
        return $this->belongsTo(Traveler::class, 'email_cliente', 'email');
    }

    /**
     * Scopes para filtrar datos
     */

    // Filtrar por tipo de reserva
    public function scopeByType($query, $type)
    {
        return $query->where('id_tipo_reserva', $type);
    }

    // Ordenar por reservas recientes
    public function scopeRecent($query)
    {
        return $query->orderBy('fecha_reserva', 'desc');
    }

    /**
     * Métodos personalizados
     */

    // Generar localizador único al crear una reserva
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->localizador = strtoupper(substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 10));
            $model->id_vehiculo = $model->id_vehiculo ?? 1;

                // Valores por defecto para campos no aplicables
            /*if ($model->id_tipo_reserva === 1) { // Aeropuerto -> Hotel
                $model->fecha_vuelo_salida = $model->fecha_vuelo_salida ?? '1970-01-01 00:00:00';
                $model->hora_vuelo_salida = $model->hora_vuelo_salida ?? '00:00:00';
            } elseif ($model->id_tipo_reserva === 2) { // Hotel -> Aeropuerto
                $model->fecha_entrada = $model->fecha_entrada ?? '1970-01-01 00:00:00';
                $model->hora_entrada = $model->hora_entrada ?? '00:00:00';
            }*/

             // Asignar valores predeterminados a numero_vuelo_entrada y origen_vuelo_entrada
            $model->numero_vuelo_entrada = $model->numero_vuelo_entrada ?? ''; // Deja vacío estos campos si la reserva es id_tipo_reserva = 1
            $model->origen_vuelo_entrada = $model->origen_vuelo_entrada ?? ''; // Valor predeterminado

        });
    }

    // Obtener todas las reservas con sus relaciones
    public static function getAllBookings()
    {
        return self::with(['hotel', 'vehicle', 'traveler'])->get();
    }

    // Obtener reservas por email del cliente
    public static function getBookingsByEmail($email)
    {
        return self::with(['hotel', 'vehicle'])->where('email_cliente', $email)->get();
    }

    // Obtener una reserva por ID
    public function getBooking($id)
    {
        $booking = Booking::findOrFail($id);
        return response()->json($booking->getFormattedData());
    }


    // Eliminar una reserva por ID
    public static function deleteBookingById($idReserva)
    {
        return self::destroy($idReserva);
    }
}
