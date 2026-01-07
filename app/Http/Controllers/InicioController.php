<?php

namespace App\Http\Controllers;

use App\Models\Inicio;
use Illuminate\Http\Request;
use App\Http\Middleware\UpdateTokenExpiration;
use Illuminate\Routing\Controller;

class InicioController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', UpdateTokenExpiration::class])->only([
            'store',
            'update',
            'destroy',
        ]);
    }
    //#region Inicio Controller de Crud PHP de Inicio
    public function index()
    {
        $Inicio = Inicio::orderBy('categoria', 'asc')->orderBy('titulo', 'asc')->orderBy('id', 'desc')->orderBy('fecha', 'desc')->get();
        return response()->json(['data' => $Inicio]);
    }


    public function store(Request $request)
    {
        $Inicio = $request->all();
        Inicio::insert($Inicio);
        return response()->json(['data' => $Inicio]);
    }

    public function show($id)
    {
        $Inicio = Inicio::where('id','=',$id)->firstOrFail();
        return response()->json(['data' => $Inicio]);
    }


    public function update(Request $request)
    {
        $Inicio = $request->all();
        Inicio::where('id','=',$request->id)->update($Inicio);
        return response()->json(['data' => $Inicio]);
    }

    public function destroy($id)
    {
        Inicio::destroy($id);
        return response()->json(['data' => 'ELIMINADO EXITOSAMENTE']);
    }
    //#endregion Fin Controller de Crud PHP de Inicio
}
