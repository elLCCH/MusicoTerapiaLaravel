<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Http\Middleware\UpdateTokenExpiration;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;

class ClienteController extends Controller
{
    public function __construct() {
        $this->middleware(UpdateTokenExpiration::class);
    }
   //#region Inicio Controller de Crud PHP de Cliente
    public function index()
    {
        $Cliente = Cliente::orderBy('id', 'desc')->get();
        return response()->json(['data' => $Cliente]);
    }


    public function store(Request $request)
    {
        $Cliente = $request->all();
        $Cliente['contrasenia']= Hash::make($request->input('contrasenia')) ;
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
        //SINO SE ENVIO EL PARAMETRO contrasenia hacer
        if ($request->has('contrasenia')) {
            //SI SE ENVIO
            //SI NO ES TIPO HASH CREAR NUEVO HASH
            if (Hash::needsRehash($request->contrasenia))
            {
                $Cliente['contrasenia'] = Hash::make($request->contrasenia);
            }
        } else {
            //NO SE ENVIO
        }
        Cliente::where('id','=',$request->id)->update($Cliente);
        return response()->json(['data' => $Cliente]);
    }

    public function destroy($id)
    {
        Cliente::destroy($id);
        return response()->json(['data' => 'ELIMINADO EXITOSAMENTE']);
    }
    //#endregion Fin Controller de Crud PHP de Cliente
}
