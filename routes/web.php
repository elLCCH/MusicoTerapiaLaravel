<?php

use App\Http\Controllers\AngularController;
use Illuminate\Support\Facades\Route;
Route::get('/blog', function () {
    return response()->file(public_path('editables/paginablog.html'));
});
   

Route::any('/{any}', [AngularController::class, 'index'])->where('any', '^(?!api).*$');
// Route::resource('api/Clientes', 'App\Http\Controllers\ClienteController');
// Route::get('/', function () {
//     return view('welcome');
// });

