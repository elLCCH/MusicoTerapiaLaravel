<?php

namespace App\Http\Controllers;

use App\Models\PlandeIntervencionsCiclos;
use Illuminate\Http\Request;
use App\Http\Middleware\UpdateTokenExpiration;
use Illuminate\Routing\Controller;

class PlandeIntervencionsCiclosController extends Controller
{
    public function __construct() {
        $this->middleware(UpdateTokenExpiration::class);
    }
    //controllerPHPlcch PlandeIntervencionsCiclos, $
    //#region Inicio Controller de Crud PHP de PlandeIntervencionsCiclos
    public function index()
    {
        $PlandeIntervencionsCiclos = PlandeIntervencionsCiclos::all();
        return response()->json(['data' => $PlandeIntervencionsCiclos]);
    }


    public function store(Request $request)
    {
        $PlandeIntervencionsCiclos = $request->all();
        PlandeIntervencionsCiclos::insert($PlandeIntervencionsCiclos);
        return response()->json(['data' => $PlandeIntervencionsCiclos]);
    }

    public function show($id)
    {
        $PlandeIntervencionsCiclos = PlandeIntervencionsCiclos::where('id','=',$id)->firstOrFail();
        return response()->json(['data' => $PlandeIntervencionsCiclos]);
    }


    public function update(Request $request)
    {
        $PlandeIntervencionsCiclos = $request->all();
        PlandeIntervencionsCiclos::where('id','=',$request->id)->update($PlandeIntervencionsCiclos);
        return response()->json(['data' => $PlandeIntervencionsCiclos]);
    }

    public function destroy($id)
    {
        PlandeIntervencionsCiclos::destroy($id);
        return response()->json(['data' => 'ELIMINADO EXITOSAMENTE']);
    }
    //#endregion Fin Controller de Crud PHP de PlandeIntervencionsCiclos
}
