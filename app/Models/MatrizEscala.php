<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class MatrizEscala extends Model
{
    use HasApiTokens, HasFactory;
    protected $table = 'matrizescalas';
    // Lista de atributos asignables
    protected $fillable = [
        'categoria',
        'nombrematriz',
        'valor'
    ];
}
