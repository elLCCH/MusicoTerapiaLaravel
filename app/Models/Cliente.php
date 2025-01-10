<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

// class Cliente extends Model
class Cliente extends Authenticatable
{
    use HasApiTokens, HasFactory;
    protected $table = 'clientes';
    // Lista de atributos asignables
    protected $fillable = [
        'nombres', 'apellidos', 'usuario', 'contrasenia', 'celular', 'edad', 'fechnac', 'carnet', 'foto','estado'
    ];
    public function createPersonalizedToken($tokenName, $abilities, $expiration, $additionalInfo = [])
    {
        $token = $this->createToken($tokenName, $abilities,$expiration);

        // Agregar información adicional al token
        $token->accessToken->forceFill($additionalInfo)->save();

        return $token;
    }
}
