<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    use HasFactory;

    protected $table = 'transfer_excursion';
    protected $primaryKey = 'id_excursion';

    public $timestamps = false;

    protected $fillable = [
        'fecha_excursion',
        'hora_entrada_excursion',
        'hora_salida_excursion',
        'descripcion',
        'num_excursionistas',
        'email_cliente',
        'id_hotel',
        'id_vehiculo',
    ];

    // Relación con Hotel
    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'id_hotel', 'id_hotel');
    }

    // Relación con Vehículo
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'id_vehiculo', 'id_vehiculo');
    }

     // Relación con la tabla de travelers
     public function traveler()
     {
         return $this->belongsTo(Traveler::class, 'email_cliente', 'email');
     }


    /**
     * Métodos personalizados
     */

    // Boot para establecer valores predeterminados
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id_vehiculo = $model->id_vehiculo ?? 1;

        });
    }
}
