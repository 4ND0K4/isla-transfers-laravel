<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Traveler extends Authenticatable
{
    use HasFactory;

    protected $table = 'transfer_viajeros'; // Nombre de la tabla en la base de datos
    protected $primaryKey = 'id_viajero'; // Clave primaria personalizada (en minúsculas)

    public $timestamps = false; // Deshabilitar timestamps (created_at y updated_at)

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'nombre',
        'apellido1',
        'apellido2',
        'direccion',
        'codigopostal',
        'ciudad',
        'pais',
        'email',
        'password'
    ];

    // Ocultar el campo password al serializar el modelo
    protected $hidden = ['password'];

    /**
     * Mutador para hashear la contraseña automáticamente al asignarla.
     *
     * @param string $password
     * @return void
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password); // Campo en minúscula
    }

    /**
     * Devuelve el campo que contiene la contraseña para la autenticación.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password; // Campo en minúscula
    }
}
