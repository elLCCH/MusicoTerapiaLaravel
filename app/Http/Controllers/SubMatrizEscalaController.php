<?php

namespace App\Http\Controllers;

use App\Models\SubMatrizEscala;
use Illuminate\Http\Request;
use App\Http\Middleware\UpdateTokenExpiration;
use Illuminate\Routing\Controller;

class SubMatrizEscalaController extends Controller
{
    public function __construct() {
        $this->middleware(UpdateTokenExpiration::class);
    }
    //#region Inicio Controller de Crud PHP de SubMatrizEscala
    public function index()
    {
        $SubMatrizEscala = SubMatrizEscala::orderBy('id_matrizescala', 'asc')->orderBy('tipo', 'asc')->orderBy('nombresubmatriz', 'asc')->get();
        return response()->json(['data' => $SubMatrizEscala]);
    }


    public function store(Request $request)
    {
        $SubMatrizEscala = $request->all();
        SubMatrizEscala::insert($SubMatrizEscala);
        return response()->json(['data' => $SubMatrizEscala]);
    }

    public function show($id)
    {
        $SubMatrizEscala = SubMatrizEscala::where('id','=',$id)->firstOrFail();
        return response()->json(['data' => $SubMatrizEscala]);
    }


    public function update(Request $request)
    {
        $SubMatrizEscala = $request->all();
        SubMatrizEscala::where('id','=',$request->id)->update($SubMatrizEscala);
        return response()->json(['data' => $SubMatrizEscala]);
    }

    public function destroy($id)
    {
        SubMatrizEscala::destroy($id);
        return response()->json(['data' => 'ELIMINADO EXITOSAMENTE']);
    }
    //#endregion Fin Controller de Crud PHP de SubMatrizEscala
    public function AllInfoSubMatrizEscalas($id) {
        $SubMatrizEscala = SubMatrizEscala::where('id_matrizescala','=',$id)->orderBy('id_matrizescala', 'asc')->orderBy('tipo', 'asc')->orderBy('nombresubmatriz', 'asc')->get();
        return response()->json(['data' => $SubMatrizEscala]);
    }
}
