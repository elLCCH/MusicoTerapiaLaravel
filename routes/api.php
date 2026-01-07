<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckAbilities;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileUploadController;



#region CODIGO PARA LA PAGINA DE BLOG
// Ruta para OBTENER el HTML
Route::get('paginablog', function () {
    $path = public_path('editables/paginablog.html');
    
    // Si no existe el archivo, lo creamos con contenido inicial
    if (!file_exists($path)) {
        file_put_contents($path, '<!DOCTYPE html><html><head><title>Mi Blog</title></head><body><h1>Bienvenido a mi blog</h1></body></html>');
    }
    
    // Devuelve el contenido del archivo
    return response()->file($path);
});

// Ruta para GUARDAR cambios en el HTML
Route::post('paginablog', function (Request $request) {
    $request->validate(['html' => 'required|string']);
    
    // Guarda el nuevo HTML en el archivo
    file_put_contents(public_path('editables/paginablog.html'), $request->html);
    
    return response()->json(['success' => true, 'message' => 'Blog actualizado correctamente.']);
});
#endregion HASTA ACA DEL BLOG



#region AUTENTICACION
Route::prefix("v1/auth")->group(function(){ //el prefijo vi/auth funciona como el routing de angular: v1/auth/login
    Route::post('/login', [AuthController::class, "login"]); //EJECUTAR LA FUNCION login desde el authcontroller
    Route::post('/logout', [AuthController::class, 'logout']); //v1/auth/logout
    // Route::post('/register', [AuthController::class, 'register']); //v1/auth/register
    // Route::post('/reset-password', [AuthController::class, 'resetPassword']); //v1/auth/reset-password
    // Route::post('/change-password', [AuthController::class, 'changePassword']); //v1/auth/change-password
    // Route::post('/forgot-password', [AuthController::class, 'forgotPassword']); //v1/auth/forgot-password
    // Route::post('/verify-email', [AuthController::class, 'verifyEmail']); //v1/auth/verify-email
    // Route::post('/resend-verification', [AuthController::class, 'resendVerification']); //v1/auth/resend-verification
    // Route::post('/update-profile', [AuthController::class, 'updateProfile']); //v1/auth/update-profile
    Route::post('/cambiar-clave', [AuthController::class, 'cambiarClave'])->middleware('auth:sanctum'); //cambiar clave de usuario ESTO SUELE SER PARA PERMITIR EL AUTORIZADO
    Route::get('/user', [AuthController::class, 'getUser'])->middleware('auth:sanctum'); //v1/auth/user
});
#endregion AUTENTICACION

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

#region PARA VERIFICAR TOKENS y ARCHIVOS FILES //SIRVE PARA LOS GUARD O SABER name DEL TOKEN DB// TAMBIEN SI YA ESTA EXPIRADO
use App\Http\Controllers\TokenController;
Route::post('/verify-token', [TokenController::class, 'verify']);

Route::post('/uploadFile', [FileUploadController::class, 'uploadFile']);
Route::post('/deleteFile', [FileUploadController::class, 'deleteFile']);
#endregion PARA VERIFICAR TOKENS

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\DatabaseQueryController;

// Route::middleware(['auth:sanctum'])->group(function () {
//     Route::get('/Clientes', [ClienteController::class, 'index'])->middleware([CheckAbilities::class . ':MUSICOTERAPEUTA']);
//     Route::get('/Clientes/{id}', [ClienteController::class, 'show'])->middleware([CheckAbilities::class . ':show-cliente']);
//     Route::post('/Clientes', [ClienteController::class, 'store'])->middleware([CheckAbilities::class . ':create-cliente']);
//     Route::put('/Clientes/{id}', [ClienteController::class, 'update'])->middleware([CheckAbilities::class . ':update-cliente']);
//     Route::delete('/Clientes/{id}', [ClienteController::class, 'destroy'])->middleware([CheckAbilities::class . ':delete-cliente']);
// });

#region RUTAS PUBLICAS 
//INICIOS CONTROLLER (PUBLICO)
Route::get('/Inicios', [InicioController::class, 'index']);
Route::get('/Inicios/{id}', [InicioController::class, 'show']);
//USUARIOS CONTROLLER (PUBLICO)
Route::get('CargarUsuariosPublico', 'App\Http\Controllers\UsuarioController@CargarUsuariosPublico');
#endregion RUTAS PUBLICAS

#region SUPERADMINISTRADOR 
Route::middleware(['auth:sanctum'])->group(function () {
    //INICIOS
    
    Route::post('/Inicios', [InicioController::class, 'store'])->middleware([CheckAbilities::class . ':SUPERADMIN']);
    Route::put('/Inicios/{id}', [InicioController::class, 'update'])->middleware([CheckAbilities::class . ':SUPERADMIN']);
    Route::delete('/Inicios/{id}', [InicioController::class, 'destroy'])->middleware([CheckAbilities::class . ':SUPERADMIN']);

    // ARCHIVOS ARCHIVOSPAGOS
    Route::get('ArchivosPagos', 'App\Http\Controllers\ArchivosPagoController@index')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A),MUSICOTERAPEUTA,ADMINLECTOR,CLIENTELA');
    Route::post('ArchivosPagos', 'App\Http\Controllers\ArchivosPagoController@store')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A)');
    Route::get('ArchivosPagos/{id}', 'App\Http\Controllers\ArchivosPagoController@show')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A),MUSICOTERAPEUTA,ADMINLECTOR,CLIENTELA');
    Route::put('ArchivosPagos/{id}', 'App\Http\Controllers\ArchivosPagoController@update')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A)');
    Route::delete('ArchivosPagos/{id}', 'App\Http\Controllers\ArchivosPagoController@destroy')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A)');
    Route::get('AllInfoArchivosPagos/{id}', 'App\Http\Controllers\ArchivosPagoController@AllInfoArchivosPagos')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A),MUSICOTERAPEUTA,ADMINLECTOR,CLIENTELA');

    // CICLOS
    Route::get('Ciclos', 'App\Http\Controllers\CicloController@index')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A),MUSICOTERAPEUTA,ADMINLECTOR,CLIENTELA');
    Route::post('Ciclos', 'App\Http\Controllers\CicloController@store')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A),MUSICOTERAPEUTA');
    Route::get('Ciclos/{id}', 'App\Http\Controllers\CicloController@show')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A),MUSICOTERAPEUTA,ADMINLECTOR,CLIENTELA');
    Route::put('Ciclos/{id}', 'App\Http\Controllers\CicloController@update')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A),MUSICOTERAPEUTA');
    Route::delete('Ciclos/{id}', 'App\Http\Controllers\CicloController@destroy')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A),MUSICOTERAPEUTA');
    Route::get('AllInfoCiclos/{id}', 'App\Http\Controllers\CicloController@AllInfoCiclos')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A),MUSICOTERAPEUTA,ADMINLECTOR,CLIENTELA');

    // CLIENTES
    Route::get('Clientes', 'App\Http\Controllers\ClienteController@index')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A),MUSICOTERAPEUTA,ADMINLECTOR,CLIENTELA');
    Route::post('Clientes', 'App\Http\Controllers\ClienteController@store')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A),MUSICOTERAPEUTA');
    Route::get('Clientes/{id}', 'App\Http\Controllers\ClienteController@show')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A),MUSICOTERAPEUTA,ADMINLECTOR,CLIENTELA');
    Route::put('Clientes/{id}', 'App\Http\Controllers\ClienteController@update')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A),MUSICOTERAPEUTA');
    Route::delete('Clientes/{id}', 'App\Http\Controllers\ClienteController@destroy')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A)');

    // INFO CLIENTES
    Route::get('InfoClientes', 'App\Http\Controllers\InfoClienteController@index')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A),MUSICOTERAPEUTA,ADMINLECTOR,CLIENTELA');
    Route::post('InfoClientes', 'App\Http\Controllers\InfoClienteController@store')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A),MUSICOTERAPEUTA');
    Route::get('InfoClientes/{id}', 'App\Http\Controllers\InfoClienteController@show')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A),MUSICOTERAPEUTA,ADMINLECTOR,CLIENTELA');
    Route::put('InfoClientes/{id}', 'App\Http\Controllers\InfoClienteController@update')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A),MUSICOTERAPEUTA');
    Route::delete('InfoClientes/{id}', 'App\Http\Controllers\InfoClienteController@destroy')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A)');
    Route::get('AllInfoClientes/{id}', 'App\Http\Controllers\InfoClienteController@AllInfoClientes')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A),MUSICOTERAPEUTA,ADMINLECTOR,CLIENTELA');

    // MATRIZ ESCALAS
    Route::get('MatrizEscalas', 'App\Http\Controllers\MatrizEscalaController@index')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A),MUSICOTERAPEUTA,ADMINLECTOR,CLIENTELA');
    Route::post('MatrizEscalas', 'App\Http\Controllers\MatrizEscalaController@store')->middleware(CheckAbilities::class . ':SUPERADMIN');
    Route::get('MatrizEscalas/{id}', 'App\Http\Controllers\MatrizEscalaController@show')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A),MUSICOTERAPEUTA,ADMINLECTOR,CLIENTELA');
    Route::put('MatrizEscalas/{id}', 'App\Http\Controllers\MatrizEscalaController@update')->middleware(CheckAbilities::class . ':SUPERADMIN');
    Route::delete('MatrizEscalas/{id}', 'App\Http\Controllers\MatrizEscalaController@destroy')->middleware(CheckAbilities::class . ':SUPERADMIN');

    // PAGOS
    Route::get('Pagos', 'App\Http\Controllers\PagoController@index')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A),MUSICOTERAPEUTA,ADMINLECTOR,CLIENTELA');
    Route::post('Pagos', 'App\Http\Controllers\PagoController@store')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A)');
    Route::get('Pagos/{id}', 'App\Http\Controllers\PagoController@show')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A),MUSICOTERAPEUTA,ADMINLECTOR,CLIENTELA');
    Route::put('Pagos/{id}', 'App\Http\Controllers\PagoController@update')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A)');
    Route::delete('Pagos/{id}', 'App\Http\Controllers\PagoController@destroy')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A)');
    Route::get('AllPagosidCliente/{id}', 'App\Http\Controllers\PagoController@AllPagosidCliente')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A),MUSICOTERAPEUTA,ADMINLECTOR,CLIENTELA');
    Route::get('AllPagosidinfoCliente/{id}', 'App\Http\Controllers\PagoController@AllPagosidinfoCliente')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A),MUSICOTERAPEUTA,ADMINLECTOR,CLIENTELA');

    // PLAN DE INTERVENCIONES CICLOS
    Route::get('PlandeIntervencionsCiclos', 'App\Http\Controllers\PlandeIntervencionsCiclosController@index')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A),MUSICOTERAPEUTA,ADMINLECTOR,CLIENTELA');
    Route::post('PlandeIntervencionsCiclos', 'App\Http\Controllers\PlandeIntervencionsCiclosController@store')->middleware(CheckAbilities::class . ':SUPERADMIN,MUSICOTERAPEUTA');
    Route::get('PlandeIntervencionsCiclos/{id}', 'App\Http\Controllers\PlandeIntervencionsCiclosController@show')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A),MUSICOTERAPEUTA,ADMINLECTOR,CLIENTELA');
    Route::put('PlandeIntervencionsCiclos/{id}', 'App\Http\Controllers\PlandeIntervencionsCiclosController@update')->middleware(CheckAbilities::class . ':SUPERADMIN,MUSICOTERAPEUTA');
    Route::delete('PlandeIntervencionsCiclos/{id}', 'App\Http\Controllers\PlandeIntervencionsCiclosController@destroy')->middleware(CheckAbilities::class . ':SUPERADMIN,MUSICOTERAPEUTA');

    // SUB MATRIZ ESCALAS
    Route::get('SubMatrizEscalas', 'App\Http\Controllers\SubMatrizEscalaController@index')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A),MUSICOTERAPEUTA,ADMINLECTOR,CLIENTELA');
    Route::post('SubMatrizEscalas', 'App\Http\Controllers\SubMatrizEscalaController@store')->middleware(CheckAbilities::class . ':SUPERADMIN');
    Route::get('SubMatrizEscalas/{id}', 'App\Http\Controllers\SubMatrizEscalaController@show')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A),MUSICOTERAPEUTA,ADMINLECTOR,CLIENTELA');
    Route::put('SubMatrizEscalas/{id}', 'App\Http\Controllers\SubMatrizEscalaController@update')->middleware(CheckAbilities::class . ':SUPERADMIN');
    Route::delete('SubMatrizEscalas/{id}', 'App\Http\Controllers\SubMatrizEscalaController@destroy')->middleware(CheckAbilities::class . ':SUPERADMIN');
    Route::get('AllInfoSubMatrizEscalas/{id}', 'App\Http\Controllers\SubMatrizEscalaController@AllInfoSubMatrizEscalas')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A),MUSICOTERAPEUTA,ADMINLECTOR,CLIENTELA');

    // PLAN DE INTERVENCIONES
    Route::get('PlandeIntervencions', 'App\Http\Controllers\PlandeIntervencionController@index')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A),MUSICOTERAPEUTA,ADMINLECTOR,CLIENTELA');
    Route::post('PlandeIntervencions', 'App\Http\Controllers\PlandeIntervencionController@store')->middleware(CheckAbilities::class . ':SUPERADMIN,MUSICOTERAPEUTA');
    Route::get('PlandeIntervencions/{id}', 'App\Http\Controllers\PlandeIntervencionController@show')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A),MUSICOTERAPEUTA,ADMINLECTOR,CLIENTELA');
    Route::put('PlandeIntervencions/{id}', 'App\Http\Controllers\PlandeIntervencionController@update')->middleware(CheckAbilities::class . ':SUPERADMIN,MUSICOTERAPEUTA');
    Route::delete('PlandeIntervencions/{id}', 'App\Http\Controllers\PlandeIntervencionController@destroy')->middleware(CheckAbilities::class . ':SUPERADMIN,MUSICOTERAPEUTA');
    Route::get('CargarPlandeIntervencionxidInfoCliente/{id}', 'App\Http\Controllers\PlandeIntervencionController@CargarPlandeIntervencionxidInfoCliente')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A),MUSICOTERAPEUTA,ADMINLECTOR,CLIENTELA');

    // SUB PLAN DE INTERVENCIONES
    Route::get('SubPlandeIntervencions', 'App\Http\Controllers\SubPlandeIntervencionController@index')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A),MUSICOTERAPEUTA,ADMINLECTOR,CLIENTELA');
    Route::post('SubPlandeIntervencions', 'App\Http\Controllers\SubPlandeIntervencionController@store')->middleware(CheckAbilities::class . ':SUPERADMIN');
    Route::get('SubPlandeIntervencions/{id}', 'App\Http\Controllers\SubPlandeIntervencionController@show')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A),MUSICOTERAPEUTA,ADMINLECTOR,CLIENTELA');
    Route::put('SubPlandeIntervencions/{id}', 'App\Http\Controllers\SubPlandeIntervencionController@update')->middleware(CheckAbilities::class . ':SUPERADMIN');
    Route::delete('SubPlandeIntervencions/{id}', 'App\Http\Controllers\SubPlandeIntervencionController@destroy')->middleware(CheckAbilities::class . ':SUPERADMIN');
    Route::get('AllInfoSubPlandeIntervencions/{id}', 'App\Http\Controllers\SubPlandeIntervencionController@AllInfoSubPlandeIntervencions')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A),MUSICOTERAPEUTA,ADMINLECTOR,CLIENTELA');
    Route::post('eliminarsubplandeintervencionsxdata','App\Http\Controllers\SubPlandeIntervencionController@eliminarsubplandeintervencionsxdata')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A)');

    // USUARIOS
    Route::get('Usuarios', 'App\Http\Controllers\UsuarioController@index')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A),MUSICOTERAPEUTA,ADMINLECTOR,CLIENTELA');
    Route::post('Usuarios', 'App\Http\Controllers\UsuarioController@store')->middleware(CheckAbilities::class . ':SUPERADMIN');
    Route::get('Usuarios/{id}', 'App\Http\Controllers\UsuarioController@show')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A),MUSICOTERAPEUTA,ADMINLECTOR,CLIENTELA');
    Route::put('Usuarios/{id}', 'App\Http\Controllers\UsuarioController@update')->middleware(CheckAbilities::class . ':SUPERADMIN');
    Route::delete('Usuarios/{id}', 'App\Http\Controllers\UsuarioController@destroy')->middleware(CheckAbilities::class . ':SUPERADMIN');
    Route::put('Usuarios/ModificarHojadeVida/{id}', 'App\Http\Controllers\UsuarioController@ModificarHojadeVida')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A),MUSICOTERAPEUTA');
    // DEMUCAS
    Route::get('Demucas', 'App\Http\Controllers\DemucasController@index')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A),MUSICOTERAPEUTA,ADMINLECTOR,CLIENTELA');
    Route::post('Demucas', 'App\Http\Controllers\DemucasController@store')->middleware(CheckAbilities::class . ':SUPERADMIN,MUSICOTERAPEUTA');
    Route::get('Demucas/{id}', 'App\Http\Controllers\DemucasController@show')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A),MUSICOTERAPEUTA,ADMINLECTOR,CLIENTELA');
    Route::put('Demucas/{id}', 'App\Http\Controllers\DemucasController@update')->middleware(CheckAbilities::class . ':SUPERADMIN,MUSICOTERAPEUTA');
    Route::delete('Demucas/{id}', 'App\Http\Controllers\DemucasController@destroy')->middleware(CheckAbilities::class . ':SUPERADMIN,MUSICOTERAPEUTA');
    Route::get('AllDemucas/{id}', 'App\Http\Controllers\DemucasController@AllDemucas')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A),MUSICOTERAPEUTA,ADMINLECTOR,CLIENTELA');
    Route::post('AddGrupoDemucas', 'App\Http\Controllers\DemucasController@AddGrupoDemucas')->middleware(CheckAbilities::class . ':SUPERADMIN,MUSICOTERAPEUTA');
    Route::post('DeleteGrupoDemucas', 'App\Http\Controllers\DemucasController@DeleteGrupoDemucas')->middleware(CheckAbilities::class . ':SUPERADMIN,MUSICOTERAPEUTA');
    Route::put('ModificarEscalaDemucas/{id}', 'App\Http\Controllers\DemucasController@ModificarEscalaDemucas')->middleware(CheckAbilities::class . ':SUPERADMIN,MUSICOTERAPEUTA');

    //EXTRAS DE SUPERADMIN
    Route::get('clientesActivosPagos', 'App\Http\Controllers\PagoController@clientesActivosPagos')->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A),ADMINLECTOR');

    // CONSULTADOR BD (solo lectura)
    Route::post('admin/sql/select', [DatabaseQueryController::class, 'select'])->middleware(CheckAbilities::class . ':SUPERADMIN,SECRETARIO(A)');    


});

#endregion SUPERADMINISTRADOR


// Route::resource('ArchivosPagos', 'App\Http\Controllers\ArchivosPagoController');
// Route::get('AllInfoArchivosPagos/{id}', 'App\Http\Controllers\ArchivosPagoController@AllInfoArchivosPagos')->middleware([CheckAbilities::class . ':show-cliente']);
// //CICLOS
// Route::resource('Ciclos', 'App\Http\Controllers\CicloController');
// Route::get('AllInfoCiclos/{id}', 'App\Http\Controllers\CicloController@AllInfoCiclos')->middleware([CheckAbilities::class . ':show-cliente']);
// //CLIENTES
// Route::resource('Clientes', 'App\Http\Controllers\ClienteController');
// //INFO CLIENTES
// Route::resource('InfoClientes', 'App\Http\Controllers\InfoClienteController');
// Route::get('AllInfoClientes/{id}', 'App\Http\Controllers\InfoClienteController@AllInfoClientes')->middleware([CheckAbilities::class . ':aaa']);

// //MATRIZ ESCALAS
// Route::resource('MatrizEscalas', 'App\Http\Controllers\MatrizEscalaController');
// //PAGOS
// Route::resource('Pagos', 'App\Http\Controllers\PagoController');
// Route::get('AllPagosidCliente/{id}', 'App\Http\Controllers\PagoController@AllPagosidCliente');
// Route::get('AllPagosidinfoCliente/{id}', 'App\Http\Controllers\PagoController@AllPagosidinfoCliente');

// Route::resource('PlandeIntervencionsCiclos', 'App\Http\Controllers\PlandeIntervencionsCiclosController');
// //SUB MATRIZ ESCALAS
// Route::resource('SubMatrizEscalas', 'App\Http\Controllers\SubMatrizEscalaController');
// Route::get('AllInfoSubMatrizEscalas/{id}', 'App\Http\Controllers\SubMatrizEscalaController@AllInfoSubMatrizEscalas')->middleware([CheckAbilities::class . ':show-cliente']);
// //PLAN DE INTERVENCIONES
// Route::resource('PlandeIntervencions', 'App\Http\Controllers\PlandeIntervencionController');
// Route::get('CargarPlandeIntervencionxidInfoCliente/{id}', 'App\Http\Controllers\PlandeIntervencionController@CargarPlandeIntervencionxidInfoCliente')->middleware([CheckAbilities::class . ':show-cliente']);

// //SUB PLAN DE INTERVENCIONES
// Route::resource('SubPlandeIntervencions', 'App\Http\Controllers\SubPlandeIntervencionController');
// Route::get('AllInfoSubPlandeIntervencions/{id}', 'App\Http\Controllers\SubPlandeIntervencionController@AllInfoSubPlandeIntervencions')->middleware([CheckAbilities::class . ':show-cliente']);
// Route::post('eliminarsubplandeintervencionsxdata','App\Http\Controllers\SubPlandeIntervencionController@eliminarsubplandeintervencionsxdata')->middleware([CheckAbilities::class . ':show-cliente']);

// Route::resource('Usuarios', 'App\Http\Controllers\UsuarioController');
// //DEMUCAS
// Route::resource('Demucas', 'App\Http\Controllers\DemucasController');
// Route::get('AllDemucas/{id}', 'App\Http\Controllers\DemucasController@AllDemucas')->middleware([CheckAbilities::class . ':show-cliente']);
// Route::post('AddGrupoDemucas', 'App\Http\Controllers\DemucasController@AddGrupoDemucas')->middleware([CheckAbilities::class . ':show-cliente']);
// Route::post('DeleteGrupoDemucas', 'App\Http\Controllers\DemucasController@DeleteGrupoDemucas')->middleware([CheckAbilities::class . ':show-cliente']);
// Route::put('ModificarEscalaDemucas/{id}', 'App\Http\Controllers\DemucasController@ModificarEscalaDemucas')->middleware([CheckAbilities::class . ':show-cliente']);




Route::get("/no-authorizado", function(){
    return response()->json(["mensaje" => "Necesitas un token de autorizacion para ver los datos"]);
})->name("login");




Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
