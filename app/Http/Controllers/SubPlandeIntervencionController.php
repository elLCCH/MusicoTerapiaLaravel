<?php

namespace App\Http\Controllers;

use App\Models\SubPlandeIntervencion;
use Illuminate\Http\Request;
use App\Http\Middleware\UpdateTokenExpiration;
use Illuminate\Routing\Controller;

class SubPlandeIntervencionController extends Controller
{
    public function __construct() {
        $this->middleware(UpdateTokenExpiration::class);
    }
    //controllerPHPlcch SubPlandeIntervencion, $
    //#region Inicio Controller de Crud PHP de SubPlandeIntervencion
    public function index()
    {
        $SubPlandeIntervencion = SubPlandeIntervencion::all();
        return response()->json(['data' => $SubPlandeIntervencion]);
    }


    public function store(Request $request)
    {
        $SubPlandeIntervencion = $request->all();
        SubPlandeIntervencion::insert($SubPlandeIntervencion);
        return response()->json(['data' => $SubPlandeIntervencion]);
    }

    public function show($id)
    {
        $SubPlandeIntervencion = SubPlandeIntervencion::where('id','=',$id)->firstOrFail();
        return response()->json(['data' => $SubPlandeIntervencion]);
    }


    public function update(Request $request)
    {
        $SubPlandeIntervencion = $request->all();
        SubPlandeIntervencion::where('id','=',$request->id)->update($SubPlandeIntervencion);
        return response()->json(['data' => $SubPlandeIntervencion]);
    }

    public function destroy($id)
    {
        SubPlandeIntervencion::destroy($id);
        return response()->json(['data' => 'ELIMINADO EXITOSAMENTE']);
    }
    //#endregion Fin Controller de Crud PHP de SubPlandeIntervencion
}
