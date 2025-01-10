<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class InfoCliente extends Model
{
    use HasApiTokens, HasFactory;
    protected $table = 'infoclientes';
    // Lista de atributos asignables
    protected $fillable = [
        'id_cliente',
        'diagnostico',
        'residenciaactual',
        'tipotratamiento',
        'duracion',
        'fechaadmision',
        'tutor',
        'frecuencia',
        'objgenerales',
        'fisico',
        'emocional',
        'cognitivo',
        'social',
        'metodosausar',
        'notas',
        'cuestionario'
    ];
}
