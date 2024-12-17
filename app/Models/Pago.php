<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class Pago extends Model
{
    use HasApiTokens, HasFactory;
    protected $table = 'pagos';
    // Lista de atributos asignables
    protected $fillable = [
        'id_infocliente',
        'precio',
        'saldo',
        'pagado',
        'horario',
        'tipo',
        'descuento'
    ];

    //LOGRAR MULTI ARRAY //REUNIR ACA
    public function ciclos() {
        return $this->hasMany(Ciclo::class, 'id_pago');
    }
}
