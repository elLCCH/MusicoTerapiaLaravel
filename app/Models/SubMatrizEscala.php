<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class SubMatrizEscala extends Model
{
    use HasApiTokens, HasFactory;
    protected $table = 'submatrizescalas';
    // Lista de atributos asignables
    protected $fillable = [
        'id_matrizescala',
        'tipo',
        'nombresubmatriz'
    ];
    //LOGRAR MULTI ARRAY
    public function matrizescala() {
        return $this->belongsTo(MatrizEscala::class, 'id_matrizescala');
    }
}
