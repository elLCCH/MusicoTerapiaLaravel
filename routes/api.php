<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckAbilities;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileUploadController;

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

Route::post('/uploadFile', [FileUploadController::class, 'uploadFile']);
Route::post('/deleteFile', [FileUploadController::class, 'deleteFile']);

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\InicioController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/Clientes', [ClienteController::class, 'index'])->middleware([CheckAbilities::class . ':view-cliente']);
    Route::get('/Clientes/{id}', [ClienteController::class, 'show'])->middleware([CheckAbilities::class . ':show-cliente']);
    Route::post('/Clientes', [ClienteController::class, 'store'])->middleware([CheckAbilities::class . ':create-cliente']);
    Route::put('/Clientes/{id}', [ClienteController::class, 'update'])->middleware([CheckAbilities::class . ':update-cliente']);
    Route::delete('/Clientes/{id}', [ClienteController::class, 'destroy'])->middleware([CheckAbilities::class . ':delete-cliente']);
});

//INICIOS CONTROLLER
Route::get('/Inicios', [InicioController::class, 'index']);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/Inicios/{id}', [InicioController::class, 'show'])->middleware([CheckAbilities::class . ':show-cliente']);
    Route::post('/Inicios', [InicioController::class, 'store'])->middleware([CheckAbilities::class . ':create-cliente']);
    Route::put('/Inicios/{id}', [InicioController::class, 'update'])->middleware([CheckAbilities::class . ':update-cliente']);
    Route::delete('/Inicios/{id}', [InicioController::class, 'destroy'])->middleware([CheckAbilities::class . ':delete-cliente']);
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


        Route::resource('MatrizEscalas', 'App\Http\Controllers\MatrizEscalaController');
        Route::resource('Pagos', 'App\Http\Controllers\PagoController');

        Route::resource('PlandeIntervencionsCiclos', 'App\Http\Controllers\PlandeIntervencionsCiclosController');
        //SUB MATRIZ ESCALAS
        Route::resource('SubMatrizEscalas', 'App\Http\Controllers\SubMatrizEscalaController');
        Route::get('AllInfoSubMatrizEscalas/{id}', 'App\Http\Controllers\SubMatrizEscalaController@AllInfoSubMatrizEscalas')->middleware([CheckAbilities::class . ':show-cliente']);
        //PLAN DE INTERVENCIONES
        Route::resource('PlandeIntervencions', 'App\Http\Controllers\PlandeIntervencionController');
        Route::get('CargarPlandeIntervencionxidInfoCliente/{id}', 'App\Http\Controllers\PlandeIntervencionController@CargarPlandeIntervencionxidInfoCliente')->middleware([CheckAbilities::class . ':show-cliente']);

        //SUB PLAN DE INTERVENCIONES
        Route::resource('SubPlandeIntervencions', 'App\Http\Controllers\SubPlandeIntervencionController');
        Route::get('AllInfoSubPlandeIntervencions/{id}', 'App\Http\Controllers\SubPlandeIntervencionController@AllInfoSubPlandeIntervencions')->middleware([CheckAbilities::class . ':show-cliente']);
        Route::post('eliminarsubplandeintervencionsxdata','App\Http\Controllers\SubPlandeIntervencionController@eliminarsubplandeintervencionsxdata')->middleware([CheckAbilities::class . ':show-cliente']);

        Route::resource('Usuarios', 'App\Http\Controllers\UsuarioController');
    });
});








Route::get("/no-authorizado", function(){
    return response()->json(["mensaje" => "Necesitas un token de autorizacion para ver los datos"]);
})->name("login");




Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
