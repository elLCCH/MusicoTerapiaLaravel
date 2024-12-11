<?php

namespace App\Http\Controllers;

use App\Models\ArchivosPago;
use Illuminate\Http\Request;
use App\Http\Middleware\UpdateTokenExpiration;
use Illuminate\Routing\Controller;

class ArchivosPagoController extends Controller
{
    public function __construct() {
        $this->middleware(UpdateTokenExpiration::class);
    }
    //#region Inicio Controller de Crud PHP de ArchivosPago
    public function index()
    {
        $ArchivosPago = ArchivosPago::all();
        return response()->json(['data' => $ArchivosPago]);
    }


    public function store(Request $request)
    {
        $ArchivosPago = $request->all();
        ArchivosPago::insert($ArchivosPago);
        return response()->json(['data' => $ArchivosPago]);
    }

    public function show($id)
    {
        $ArchivosPago = ArchivosPago::where('id','=',$id)->firstOrFail();
        return response()->json(['data' => $ArchivosPago]);
    }


    public function update(Request $request)
    {
        $ArchivosPago = $request->all();
        ArchivosPago::where('id','=',$request->id)->update($ArchivosPago);
        return response()->json(['data' => $ArchivosPago]);
    }

    public function destroy($id)
    {
        ArchivosPago::destroy($id);
        return response()->json(['data' => 'ELIMINADO EXITOSAMENTE']);
    }
    //#endregion Fin Controller de Crud PHP de ArchivosPago
}
