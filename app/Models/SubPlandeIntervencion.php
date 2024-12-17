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
    //LOGRAR MULTI ARRAY //USAR PARA INCORPORACION
    public function plandeintervencion() {
        return $this->belongsTo(PlandeIntervencion::class, 'id_plandeintervencion');
    }
}
