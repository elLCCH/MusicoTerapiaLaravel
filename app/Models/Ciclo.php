<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class Ciclo extends Model
{
    use HasApiTokens, HasFactory;
    protected $table = 'ciclos';
    // Lista de atributos asignables
    protected $fillable = [
        'id_pago',
        'nrociclo',
        'sesion',
        'estadosesion',
        'fecha',
        'estadopago',
        'eri',
        'cim'
    ];
    //LOGRAR MULTI ARRAY
    public function pago() {
        return $this->belongsTo(Pago::class, 'id_pago');
    }
}
