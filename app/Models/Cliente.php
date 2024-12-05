<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

// class Cliente extends Model
class Cliente extends Authenticatable
{
    // // Especificar el nombre de la tabla
    // protected $table = 'clientes';
    // // use HasFactory;
    use HasApiTokens, HasFactory;
    protected $table = 'clientes';
    // Lista de atributos asignables
    protected $fillable = [
        'nombres', 'apellidos', 'usuario', 'contrasenia', 'celular', 'edad', 'fechnac', 'carnet', 'foto'
    ];
}
