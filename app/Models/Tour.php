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
}
