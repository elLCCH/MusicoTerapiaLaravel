<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class Inicio extends Model
{
    use HasApiTokens, HasFactory;
    protected $table = 'inicios';
    // Lista de atributos asignables
    protected $fillable = [
        'archivo',
        'etiqueta',
        'titulo',
        'subtitulo',
        'descripcion',
        'categoria',
        'link',
        'costo',
        'duracion',
        'cupos',
        'fecha'
    ];
}
