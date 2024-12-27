<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class ArchivosPago extends Model
{
    use HasApiTokens, HasFactory;
    protected $table = 'archivospagos';
    // Lista de atributos asignables
    protected $fillable = [
        'id_pago',
        'monto',
        'fechapago',
        'horapago',
        'file',
        'observacion',
        'estadopago'
    ];
}
