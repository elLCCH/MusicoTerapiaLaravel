<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class PlandeIntervencion extends Model
{
    use HasApiTokens, HasFactory;
    protected $table = 'plandeintervencions';
    // Lista de atributos asignables
    protected $fillable = [
        'id_infocliente',
        'momento',
        'objetivo',
        'foco',
        'mlt',
        'enfoque',
        'duracion'
    ];
}
