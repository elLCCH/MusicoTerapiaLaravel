<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use App\Http\Middleware\UpdateTokenExpiration;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function __construct() {
        $this->middleware(UpdateTokenExpiration::class);
    }
    //#region Inicio Controller de Crud PHP de Usuario
    public function index()
    {
        $Usuario = Usuario::orderBy('estado', 'asc')->orderBy('apellidos', 'asc')->orderBy('nombres', 'asc')->get();
        return response()->json(['data' => $Usuario]);
    }


    public function store(Request $request)
    {
        $Usuario = $request->all();
        $Usuario['contrasenia']= Hash::make($request->input('contrasenia')) ;

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

        // Si se envió el parámetro 'contrasenia'
        if ($request->has('contrasenia')) {
            $contrasenia = $request->input('contrasenia');
            // Si la contraseña no está hasheada, la hasheamos
            if (!Hash::info($contrasenia)['algo'] || Hash::needsRehash($contrasenia)) {
                $Usuario['contrasenia'] = Hash::make($contrasenia);
            } else {
                $Usuario['contrasenia'] = $contrasenia;
            }
        } else {
            // Si no se envió, eliminamos del array para no sobrescribir
            unset($Usuario['contrasenia']);
        }

        Usuario::where('id', '=', $request->id)->update($Usuario);
        return response()->json(['data' => $Usuario]);
    }

    public function destroy($id)
    {
        Usuario::destroy($id);
        return response()->json(['data' => 'ELIMINADO EXITOSAMENTE']);
    }


    public function ModificarHojadeVida(Request $request, $id)
    {

        Usuario::where('id', $id)->update(['hojadevida' => $request->hojadevida]);

        return response()->json(['message' => 'Hoja de vida actualizada correctamente']);
    }


    //////PARA CARGAR USUARIOS PARA PUBLICO
    public function CargarUsuariosPublico()
    {
        $usuarios = Usuario::select('nombres', 'apellidos', 'estado', 'celulartrabajo', 'foto', 'tipo','funciones', 'hojadevida','visibilidad')
            ->orderBy('estado', 'asc')
            ->orderBy('apellidos', 'asc')
            ->orderBy('nombres', 'asc')
            ->get();
        
        return response()->json(['data' => $usuarios]);
    }

    //#endregion Fin Controller de Crud PHP de Usuario
}
