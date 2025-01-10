<?php

namespace App\Http\Controllers;

use App\Models\Ciclo;
use Illuminate\Http\Request;
use App\Http\Middleware\UpdateTokenExpiration;
use Illuminate\Routing\Controller;

class CicloController extends Controller
{
    public function __construct() {
        $this->middleware(UpdateTokenExpiration::class);
    }
    //#region Inicio Controller de Crud PHP de Ciclo
    public function index()
    {
        $Ciclo = Ciclo::orderBy('nrociclo', 'asc')->orderBy('sesion', 'asc')->get();
        return response()->json(['data' => $Ciclo]);
    }


    public function store(Request $request)
    {
        $Ciclo = $request->all();
        Ciclo::insert($Ciclo);
        return response()->json(['data' => $Ciclo]);
    }

    public function show($id)
    {
        $Ciclo = Ciclo::where('id','=',$id)->firstOrFail();
        return response()->json(['data' => $Ciclo]);
    }


    public function update(Request $request)
    {
        $Ciclo = $request->all();
        Ciclo::where('id','=',$request->id)->update($Ciclo);
        return response()->json(['data' => $Ciclo]);
    }

    public function destroy($id)
    {
        Ciclo::destroy($id);
        return response()->json(['data' => 'ELIMINADO EXITOSAMENTE']);
    }
    //#endregion Fin Controller de Crud PHP de Ciclo
    public function AllInfoCiclos($id) {
        $Ciclos = Ciclo::where('id_pago','=',$id)->orderBy('nrociclo', 'asc')->orderBy('sesion', 'asc')->get();
        return response()->json(['data' => $Ciclos]);
    }
}
