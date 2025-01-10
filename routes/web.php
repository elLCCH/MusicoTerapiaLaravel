<?php

use App\Http\Controllers\AngularController;
use Illuminate\Support\Facades\Route;
Route::any('/{any}', [AngularController::class, 'index'])->where('any', '^(?!api).*$');
// Route::resource('api/Clientes', 'App\Http\Controllers\ClienteController');
// Route::get('/', function () {
//     return view('welcome');
// });
