<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;

    protected $table = 'transfer_precios'; // Tabla asociada
    protected $primaryKey = 'id_precios'; // Clave primaria

    public $timestamps = false;

    protected $fillable = [
        'id_vehiculo',
        'id_hotel',
        'precio'
    ];

    // Relación con Hotel
    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'id_hotel');
    }

    // Relación con Vehiculo
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'id_vehiculo');
    }
}

