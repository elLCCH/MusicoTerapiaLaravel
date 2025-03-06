<?php

namespace App\Http\Controllers;

use App\Models\Demucas;
use Illuminate\Http\Request;
use App\Http\Middleware\UpdateTokenExpiration;
use Illuminate\Routing\Controller;


class DemucasController extends Controller
{
    //controllerPHPlcch Demucas, $
    public function __construct() {
        $this->middleware(UpdateTokenExpiration::class);
    }
    //#region Inicio Controller de Crud PHP de Demucas
    public function index()
    {
        $Demucas = Demucas::all();
        return response()->json(['data' => $Demucas]);
    }


    public function store(Request $request)
    {
        $Demucas = $request->all();
        Demucas::insert($Demucas);
        return response()->json(['data' => $Demucas]);
    }

    public function show($id)
    {
        $Demucas = Demucas::where('id','=',$id)->firstOrFail();
        return response()->json(['data' => $Demucas]);
    }


    public function update(Request $request)
    {
        $Demucas = $request->all();
        Demucas::where('id','=',$request->id)->update($Demucas);
        return response()->json(['data' => $Demucas]);
    }

    public function destroy($id)
    {
        Demucas::destroy($id);
        return response()->json(['data' => 'ELIMINADO EXITOSAMENTE']);
    }
    //#endregion Fin Controller de Crud PHP de Demucas
    public function AllDemucas($id) {
        $InfoCliente = Demucas::where('id_infocliente','=',$id)->orderBy('evaluacion', 'asc')->orderBy('rango', 'asc')->get();
        return response()->json(['data' => $InfoCliente]);
    }


    public function AddGrupoDemucas(Request $request) {
        $Demucas = $request->all();
        foreach ($Demucas as $demuca) {
            Demucas::create($demuca);
        }
        return response()->json(['data' => $Demucas]);
    }
    public function DeleteGrupoDemucas(Request $request) {

        //EN CASO DE QUE LLEGUE ASI LOS IDS:[5,4,6] ELIMINAR ASI....
        $ids = $request->all();
        Demucas::whereIn('id', $ids)->delete();
        return response()->json(['data' => 'ELIMINADO EXITOSAMENTE']);
    }
}
