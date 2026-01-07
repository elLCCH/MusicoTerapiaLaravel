<?php

namespace App\Http\Controllers;

use App\Models\MatrizEscala;
use Illuminate\Http\Request;
use App\Http\Middleware\UpdateTokenExpiration;
use Illuminate\Routing\Controller;

class MatrizEscalaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', UpdateTokenExpiration::class]);
    }
    //controllerPHPlcch MatrizEscala, $
    //#region Inicio Controller de Crud PHP de MatrizEscala
    public function index()
    {
        // $MatrizEscala = MatrizEscala::all();
        $MatrizEscala = MatrizEscala::with('submatrizescala')->orderBy('categoria', 'asc')->orderBy('nombrematriz', 'asc')->get();
        return response()->json(['data' => $MatrizEscala]);
    }


    public function store(Request $request)
    {
        $MatrizEscala = $request->all();
        MatrizEscala::insert($MatrizEscala);
        return response()->json(['data' => $MatrizEscala]);
    }

    public function show($id)
    {
        $MatrizEscala = MatrizEscala::where('id','=',$id)->firstOrFail();
        return response()->json(['data' => $MatrizEscala]);
    }


    public function update(Request $request)
    {
        $MatrizEscala = $request->all();
        MatrizEscala::where('id','=',$request->id)->update($MatrizEscala);
        return response()->json(['data' => $MatrizEscala]);
    }

    public function destroy($id)
    {
        MatrizEscala::destroy($id);
        return response()->json(['data' => 'ELIMINADO EXITOSAMENTE']);
    }
    //#endregion Fin Controller de Crud PHP de MatrizEscala
}
