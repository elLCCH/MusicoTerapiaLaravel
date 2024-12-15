<?php

namespace App\Http\Controllers;

use App\Models\InfoCliente;
use Illuminate\Http\Request;
use App\Http\Middleware\UpdateTokenExpiration;
use Illuminate\Routing\Controller;

class InfoClienteController extends Controller
{
    public function __construct() {
        $this->middleware(UpdateTokenExpiration::class);
    }
    //#region Inicio Controller de Crud PHP de InfoCliente
    public function index()
    {
        $InfoCliente = InfoCliente::all();
        return response()->json(['data' => $InfoCliente]);
    }


    public function store(Request $request)
    {
        $InfoCliente = $request->all();
        InfoCliente::insert($InfoCliente);
        return response()->json(['data' => $InfoCliente]);
    }

    public function show($id)
    {
        $InfoCliente = InfoCliente::where('id','=',$id)->firstOrFail();
        return response()->json(['data' => $InfoCliente]);
    }


    public function update(Request $request)
    {
        $InfoCliente = $request->all();
        InfoCliente::where('id','=',$request->id)->update($InfoCliente);
        return response()->json(['data' => $InfoCliente]);
    }

    public function destroy($id)
    {
        InfoCliente::destroy($id);
        return response()->json(['data' => 'ELIMINADO EXITOSAMENTE']);
    }
    //#endregion Fin Controller de Crud PHP de InfoCliente

    public function AllInfoClientes($id) {
        $InfoCliente = InfoCliente::where('id_cliente','=',$id)->get();
        return response()->json(['data' => $InfoCliente]);
    }
}
