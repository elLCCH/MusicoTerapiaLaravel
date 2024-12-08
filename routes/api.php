<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Middleware\CheckAbilities;



// Route::get('/', function () {
//     return view('welcome');
// });

use App\Http\Controllers\AuthController;
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix("v1/auth")->group(function(){ //el prefijo vi/auth funciona como el routing de angular: v1/auth/login
    Route::post('/login', [AuthController::class, "login"]); //EJECUTAR LA FUNCION login desde el authcontroller
    Route::post('/logout', [AuthController::class, 'logout']); //v1/auth/logout
    // Route::middleware(['auth:sanctum'])->group(function(){ //middleware se usa para verificar si tienes token, si no tienes no puedes entrar
    //     Route::post('/logout', [AuthController::class, 'logout']); //v1/auth/logout
    // });
});
// Route::middleware("auth:sanctum")->group(function(){
//     Route::resource('Clientes', ClienteController::class);
// });

Route::middleware(['auth:sanctum'])->group(function () {
    // Ruta para clientes con habilidades específicas
    Route::middleware([CheckAbilities::class . ':view-cliente'])->group(function () {
        Route::get('/Clientes', [ClienteController::class, 'index']);
    });

    Route::middleware([CheckAbilities::class . ':view-cliente'])->group(function () {
        Route::get('/Clientes/{id}', [ClienteController::class, 'show']);
    });

    Route::middleware([CheckAbilities::class . ':create-cliente'])->group(function () {
        Route::post('/Clientes', [ClienteController::class, 'store']);
    });

    Route::middleware([CheckAbilities::class . ':update-cliente'])->group(function () {
        Route::put('/Clientes/{id}', [ClienteController::class, 'update']);
    });

    Route::middleware([CheckAbilities::class . ':delete-cliente'])->group(function () {
        Route::delete('/Clientes/{id}', [ClienteController::class, 'destroy']);
    });
});

//PARA VERIFICAR TOKENS //SIRVE PARA LOS GUARD O SABER name DEL TOKEN DB// TAMBIEN SI YA ESTA EXPIRADO
use App\Http\Controllers\TokenController;
Route::post('/verify-token', [TokenController::class, 'verify']);



// Route::middleware(['auth:sanctum'])->group(function () {
//     Route::get('/Clientes', [ClienteController::class, 'index'])->middleware([CheckAbilities::class . ':view-cliente']);
//     Route::post('/Clientes', [ClienteController::class, 'store'])->middleware([CheckAbilities::class . ':create-cliente']);
//     Route::put('/Clientes/{id}', [ClienteController::class, 'update'])->middleware([CheckAbilities::class . ':update-cliente']);
//     Route::delete('/Clientes/{id}', [ClienteController::class, 'destroy'])->middleware([CheckAbilities::class . ':delete-cliente']);
// });









Route::get("/no-authorizado", function(){
    return response()->json(["mensaje" => "Necesitas un token de autorizacion para ver los datos"]);
})->name("login");




Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
