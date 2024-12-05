<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
   //#region Inicio Controller de Crud PHP de Cliente
    public function index()
    {
        $Cliente = Cliente::all();
        return response()->json(['data' => $Cliente]);
    }


    public function store(Request $request)
    {
        $Cliente = $request->all();
        Cliente::insert($Cliente);
        return response()->json(['data' => $Cliente]);
    }

    public function show($id)
    {
        $Cliente = Cliente::where('id','=',$id)->firstOrFail();
        return response()->json(['data' => $Cliente]);
    }


    public function update(Request $request)
    {
        $Cliente = $request->all();
        Cliente::where('id','=',$request->id)->update($Cliente);
        return response()->json(['data' => $Cliente]);
    }

    public function destroy($id)
    {
        Cliente::destroy($id);
        return response()->json(['data' => 'ELIMINADO EXITOSAMENTE']);
    }
    //#region Fin Controller de Crud PHP de Cliente
}
