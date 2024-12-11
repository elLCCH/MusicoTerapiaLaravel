<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use App\Http\Middleware\UpdateTokenExpiration;
use Illuminate\Routing\Controller;

class UsuarioController extends Controller
{
    public function __construct() {
        $this->middleware(UpdateTokenExpiration::class);
    }
    //#region Inicio Controller de Crud PHP de Usuario
    public function index()
    {
        $Usuario = Usuario::all();
        return response()->json(['data' => $Usuario]);
    }


    public function store(Request $request)
    {
        $Usuario = $request->all();
        Usuario::insert($Usuario);
        return response()->json(['data' => $Usuario]);
    }

    public function show($id)
    {
        $Usuario = Usuario::where('id','=',$id)->firstOrFail();
        return response()->json(['data' => $Usuario]);
    }


    public function update(Request $request)
    {
        $Usuario = $request->all();
        Usuario::where('id','=',$request->id)->update($Usuario);
        return response()->json(['data' => $Usuario]);
    }

    public function destroy($id)
    {
        Usuario::destroy($id);
        return response()->json(['data' => 'ELIMINADO EXITOSAMENTE']);
    }
    //#endregion Fin Controller de Crud PHP de Usuario
}
