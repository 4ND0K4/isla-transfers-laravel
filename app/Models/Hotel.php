<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Hotel extends Authenticatable
{
    use HasFactory;

    protected $table = 'transfer_hotel';
    protected $primaryKey = 'id_hotel';

    public $timestamps = false;

    protected $fillable = [
        'id_zona',
        'comision',
        'usuario',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getAuthIdentifierName()
{
    return 'usuario';
}

  // Relación con las reservas
  public function bookings()
  {
      return $this->hasMany(Booking::class, 'id_hotel');
  }

  // Relación con los precios
  public function precios()
  {
      return $this->hasMany(TransferPrecio::class, 'id_hotel');
  }

}
