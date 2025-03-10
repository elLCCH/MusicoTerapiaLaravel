<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Database\Eloquent\Model;

class Demucas extends Model
{
    use HasApiTokens, HasFactory;
    protected $table = 'demucas';
    // Lista de atributos asignables
    protected $fillable = [
        'id_infocliente',
        'categoria',
        'evaluacion',
        'rango',
        'palabra',
        'escala',
        'fecha'
    ];
}
