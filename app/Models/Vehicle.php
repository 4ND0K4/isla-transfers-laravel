<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Vehicle extends Authenticatable
{
    use HasFactory;

    protected $table = 'transfer_vehiculo';
    protected $primaryKey = 'id_vehiculo';

    public $timestamps = false;

    protected $fillable = [
        'descripcion',
        'email_conductor',
        'password',
    ];

    protected $hidden = [
        'password',
    ];
}
