<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class SubPlandeIntervencion extends Model
{
    use HasApiTokens, HasFactory;
    protected $table = 'subplandeintervencions';
    // Lista de atributos asignables
    protected $fillable = [
        'id_plandeintervencion',
        'categoria',
        'nombre'
    ];
}
