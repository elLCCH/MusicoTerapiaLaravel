<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class PlandeIntervencionsCiclos extends Model
{
    use HasApiTokens, HasFactory;
    protected $table = 'plandeintervencionsciclos';
    // Lista de atributos asignables
    protected $fillable = [
        'id_plandeintervencion',
        'id_ciclo',
        'ejecucion',
        'apuntes'
    ];
}
