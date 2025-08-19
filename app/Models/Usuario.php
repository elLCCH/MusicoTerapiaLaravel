<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory;
    protected $table = 'usuarios';
    // Lista de atributos asignables
    protected $fillable = [
        'nombres',
        'apellidos',
        'usuario',
        'contrasenia',
        'celular',
        'celulartrabajo',
        'carnet',
        'foto',
        'tipo','estado',
        'funciones', 'hojadevida','visibilidad'
    ];
    public function createPersonalizedToken($tokenName, $abilities, $expiration, $additionalInfo = [])
    {
        $token = $this->createToken($tokenName, $abilities,$expiration);

        // Agregar información adicional al token
        $token->accessToken->forceFill($additionalInfo)->save();

        return $token;
    }
}
