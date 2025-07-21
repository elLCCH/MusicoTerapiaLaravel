<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckAbilities;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileUploadController;



//CODIGO PARA LA PAGINA DE BLOG
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
//HASTA ACA DEL BLOG




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

        //ARCHIVOS ARCHIVOSPAGOS
        Route::resource('ArchivosPagos', 'App\Http\Controllers\ArchivosPagoController');
        Route::get('AllInfoArchivosPagos/{id}', 'App\Http\Controllers\ArchivosPagoController@AllInfoArchivosPagos')->middleware([CheckAbilities::class . ':show-cliente']);
        //CICLOS
        Route::resource('Ciclos', 'App\Http\Controllers\CicloController');
        Route::get('AllInfoCiclos/{id}', 'App\Http\Controllers\CicloController@AllInfoCiclos')->middleware([CheckAbilities::class . ':show-cliente']);
        //INFO CLIENTES
        Route::resource('InfoClientes', 'App\Http\Controllers\InfoClienteController');
        Route::get('AllInfoClientes/{id}', 'App\Http\Controllers\InfoClienteController@AllInfoClientes')->middleware([CheckAbilities::class . ':aaa']);

        //MATRIZ ESCALAS
        Route::resource('MatrizEscalas', 'App\Http\Controllers\MatrizEscalaController');
        //PAGOS
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
        //DEMUCAS
        Route::resource('Demucas', 'App\Http\Controllers\DemucasController');
        Route::get('AllDemucas/{id}', 'App\Http\Controllers\DemucasController@AllDemucas')->middleware([CheckAbilities::class . ':show-cliente']);
        Route::post('AddGrupoDemucas', 'App\Http\Controllers\DemucasController@AddGrupoDemucas')->middleware([CheckAbilities::class . ':show-cliente']);
        Route::post('DeleteGrupoDemucas', 'App\Http\Controllers\DemucasController@DeleteGrupoDemucas')->middleware([CheckAbilities::class . ':show-cliente']);
        Route::put('ModificarEscalaDemucas/{id}', 'App\Http\Controllers\DemucasController@ModificarEscalaDemucas')->middleware([CheckAbilities::class . ':show-cliente']);
    });
});

//SOLO SUPER ADMINISTRADOR
Route::middleware(['auth:sanctum'])->group(function () {
    Route::middleware([CheckAbilities::class . ':Clientela'])->group(function () {

        //ARCHIVOS ARCHIVOSPAGOS
        Route::resource('ArchivosPagos', 'App\Http\Controllers\ArchivosPagoController');
        Route::get('AllInfoArchivosPagos/{id}', 'App\Http\Controllers\ArchivosPagoController@AllInfoArchivosPagos');
        //CICLOS
        Route::resource('Ciclos', 'App\Http\Controllers\CicloController');
        Route::get('AllInfoCiclos/{id}', 'App\Http\Controllers\CicloController@AllInfoCiclos');
        //INFO CLIENTES
        Route::resource('InfoClientes', 'App\Http\Controllers\InfoClienteController');
        Route::get('AllInfoClientes/{id}', 'App\Http\Controllers\InfoClienteController@AllInfoClientes');

        //MATRIZ ESCALAS
        Route::resource('MatrizEscalas', 'App\Http\Controllers\MatrizEscalaController');
        //PAGOS
        Route::resource('Pagos', 'App\Http\Controllers\PagoController');
        Route::get('AllPagosidCliente/{id}', 'App\Http\Controllers\PagoController@AllPagosidCliente');

        Route::resource('PlandeIntervencionsCiclos', 'App\Http\Controllers\PlandeIntervencionsCiclosController');
        //SUB MATRIZ ESCALAS
        Route::resource('SubMatrizEscalas', 'App\Http\Controllers\SubMatrizEscalaController');
        Route::get('AllInfoSubMatrizEscalas/{id}', 'App\Http\Controllers\SubMatrizEscalaController@AllInfoSubMatrizEscalas');
        //PLAN DE INTERVENCIONES
        Route::resource('PlandeIntervencions', 'App\Http\Controllers\PlandeIntervencionController');
        Route::get('CargarPlandeIntervencionxidInfoCliente/{id}', 'App\Http\Controllers\PlandeIntervencionController@CargarPlandeIntervencionxidInfoCliente');

        //SUB PLAN DE INTERVENCIONES
        Route::resource('SubPlandeIntervencions', 'App\Http\Controllers\SubPlandeIntervencionController');
        Route::get('AllInfoSubPlandeIntervencions/{id}', 'App\Http\Controllers\SubPlandeIntervencionController@AllInfoSubPlandeIntervencions');
        Route::post('eliminarsubplandeintervencionsxdata','App\Http\Controllers\SubPlandeIntervencionController@eliminarsubplandeintervencionsxdata');

        Route::resource('Usuarios', 'App\Http\Controllers\UsuarioController');
        //DEMUCAS
        Route::resource('Demucas', 'App\Http\Controllers\DemucasController');
        Route::get('AllDemucas/{id}', 'App\Http\Controllers\DemucasController@AllDemucas');
        Route::post('AddGrupoDemucas', 'App\Http\Controllers\DemucasController@AddGrupoDemucas');
        Route::post('DeleteGrupoDemucas', 'App\Http\Controllers\DemucasController@DeleteGrupoDemucas');
        Route::put('ModificarEscalaDemucas/{id}', 'App\Http\Controllers\DemucasController@ModificarEscalaDemucas');
    });
});








Route::get("/no-authorizado", function(){
    return response()->json(["mensaje" => "Necesitas un token de autorizacion para ver los datos"]);
})->name("login");




Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
