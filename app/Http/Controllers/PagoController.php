<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use Illuminate\Http\Request;
use App\Http\Middleware\UpdateTokenExpiration;
use Illuminate\Routing\Controller;

class PagoController extends Controller
{
    public function __construct() {
        $this->middleware(UpdateTokenExpiration::class);
    }
    //controllerPHPlcch Pago, $
    //#region Inicio Controller de Crud PHP de Pago
    public function index()
    {
        // $Pago = Pago::all();
        $Pago = Pago::with('ciclos')->get();
        return response()->json(['data' => $Pago]);
    }


    public function store(Request $request)
    {
        $Pago = $request->all();
        Pago::insert($Pago);
        return response()->json(['data' => $Pago]);
    }

    public function show($id)
    {
        $Pago = Pago::where('id','=',$id)->firstOrFail();
        return response()->json(['data' => $Pago]);
    }


    public function update(Request $request)
    {
        $Pago = $request->all();
        Pago::where('id','=',$request->id)->update($Pago);
        return response()->json(['data' => $Pago]);
    }

    public function destroy($id)
    {
        Pago::destroy($id);
        return response()->json(['data' => 'ELIMINADO EXITOSAMENTE']);
    }
    //#endregion Fin Controller de Crud PHP de Pago
}
