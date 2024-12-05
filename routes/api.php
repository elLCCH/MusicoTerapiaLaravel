<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;




// Route::get('/', function () {
//     return view('welcome');
// });

use App\Http\Controllers\AuthController;
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix("v1/auth")->group(function(){ //el prefijo vi/auth funciona como el routing de angular: v1/auth/login

    Route::post('/login', [AuthController::class, "login"]); //EJECUTAR LA FUNCION login desde el authcontroller
    Route::post('/registro', [AuthController::class, "registro"]);

    Route::middleware("auth:sanctum")->group(function(){ //middleware se usa para verificar si tienes token, si no tienes no puedes entrar
        Route::post('/logout', [AuthController::class, "logout"]);
        Route::get('/perfil', [AuthController::class, "perfil"]);
    });

});
Route::middleware("auth:sanctum")->group(function(){
    // Route::resource('Clientes', 'App\Http\Controllers\ClienteController');
    Route::resource('Clientes', ClienteController::class);
});


// Route::middleware("auth:sanctum")->group(function(){

//     Route::post('/producto/{id}/actualizar-imagen', [ProductoController::class, "actualizarImagen"]);

//     Route::apiResource("categoria", CategoriaController::class);
//     Route::apiResource("producto", ProductoController::class);
//     Route::apiResource("cliente", ClienteController::class);
//     Route::apiResource("pedido", PedidoController::class);
// });

// Route::get("/pdf", [PedidoController::class, "reportePedidos"]);

// Route::post("recuperar-password", [NuevoPasswordController::class, "recuperarPassword"]);
// Route::post("reset-password", [NuevoPasswordController::class, "reset"]);


Route::get("/no-authorizado", function(){
    return response()->json(["mensaje" => "Necesitas un token de autorizacion para ver los datos"]);
})->name("login");




Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
