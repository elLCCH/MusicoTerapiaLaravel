<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckAbilities;
use App\Http\Controllers\AuthController;

Route::prefix("v1/auth")->group(function(){ //el prefijo vi/auth funciona como el routing de angular: v1/auth/login
    Route::post('/login', [AuthController::class, "login"]); //EJECUTAR LA FUNCION login desde el authcontroller
    Route::post('/logout', [AuthController::class, 'logout']); //v1/auth/logout
});
// Route::middleware("auth:sanctum")->group(function(){
//     Route::resource('Clientes', ClienteController::class);
// });

// Route::middleware(['auth:sanctum'])->group(function () {
//     // Ruta para clientes con habilidades específicas
//     Route::middleware([CheckAbilities::class . ':view-cliente'])->group(function () {
//         Route::get('/Clientes', [ClienteController::class, 'index']);
//     });

//     Route::middleware([CheckAbilities::class . ':view-cliente'])->group(function () {
//         Route::get('/Clientes/{id}', [ClienteController::class, 'show']);
//     });

//     Route::middleware([CheckAbilities::class . ':create-cliente'])->group(function () {
//         Route::post('/Clientes', [ClienteController::class, 'store']);
//     });

//     Route::middleware([CheckAbilities::class . ':update-cliente'])->group(function () {
//         Route::put('/Clientes/{id}', [ClienteController::class, 'update']);
//     });

//     Route::middleware([CheckAbilities::class . ':delete-cliente'])->group(function () {
//         Route::delete('/Clientes/{id}', [ClienteController::class, 'destroy']);
//     });
// });

//PARA VERIFICAR TOKENS //SIRVE PARA LOS GUARD O SABER name DEL TOKEN DB// TAMBIEN SI YA ESTA EXPIRADO
use App\Http\Controllers\TokenController;
Route::post('/verify-token', [TokenController::class, 'verify']);


use App\Http\Controllers\ClienteController;
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/Clientes', [ClienteController::class, 'index'])->middleware([CheckAbilities::class . ':view-cliente']);
    Route::get('/Clientes/{id}', [ClienteController::class, 'show'])->middleware([CheckAbilities::class . ':show-cliente']);
    Route::post('/Clientes', [ClienteController::class, 'store'])->middleware([CheckAbilities::class . ':create-cliente']);
    Route::put('/Clientes/{id}', [ClienteController::class, 'update'])->middleware([CheckAbilities::class . ':update-cliente']);
    Route::delete('/Clientes/{id}', [ClienteController::class, 'destroy'])->middleware([CheckAbilities::class . ':delete-cliente']);
});

//SOLO SUPER ADMINISTRADOR
Route::middleware(['auth:sanctum'])->group(function () {
    Route::middleware([CheckAbilities::class . ':superadmin'])->group(function () {
        //ARCHIVOS PAGOS
        Route::resource('ArchivosPagos', 'App\Http\Controllers\ArchivosPagoController');
        Route::get('AllInfoArchivosPagos/{id}', 'App\Http\Controllers\ArchivosPagoController@AllInfoArchivosPagos')->middleware([CheckAbilities::class . ':show-cliente']);
        //CICLOS
        Route::resource('Ciclos', 'App\Http\Controllers\CicloController');
        Route::get('AllInfoCiclos/{id}', 'App\Http\Controllers\CicloController@AllInfoCiclos')->middleware([CheckAbilities::class . ':show-cliente']);
        //INFO CLIENTES
        Route::resource('InfoClientes', 'App\Http\Controllers\InfoClienteController');
        Route::get('AllInfoClientes/{id}', 'App\Http\Controllers\InfoClienteController@AllInfoClientes')->middleware([CheckAbilities::class . ':show-cliente']);

        Route::resource('Inicios', 'App\Http\Controllers\InicioController');
        Route::resource('MatrizEscalas', 'App\Http\Controllers\MatrizEscalaController');
        Route::resource('Pagos', 'App\Http\Controllers\PagoController');
        Route::resource('PlandeIntervencions', 'App\Http\Controllers\PlandeIntervencionController');
        Route::resource('PlandeIntervencionsCiclos', 'App\Http\Controllers\PlandeIntervencionsCiclosController');
        Route::resource('SubMatrizEscalas', 'App\Http\Controllers\SubMatrizEscalaController');
        Route::resource('SubPlandeIntervencions', 'App\Http\Controllers\SubPlandeIntervencionController');
        Route::resource('Usuarios', 'App\Http\Controllers\UsuarioController');
    });
});








Route::get("/no-authorizado", function(){
    return response()->json(["mensaje" => "Necesitas un token de autorizacion para ver los datos"]);
})->name("login");




Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
