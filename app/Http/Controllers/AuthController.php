<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        // validar
        $request->validate([
            "usuario" => "required",
            "contrasenia" => "required",
        ]);

        // verificar
        $user = $request->input('usuario');
        $pass = $request->input('contrasenia');
        $login =false;
        // $admin = Cliente::where('usuario', '=', $user)->where('contrasenia', '=', $pass)->first();
        // $admin = Usuario::where('usuario', '=', $user)->where('contrasenia', '=', $pass)->first();


        $sesion = Usuario::where('usuario','=', $user)->first();

        try {
            if (Hash::check($pass, $sesion->contrasenia)) {
                // INICIO DE SESION CORRECTO
                $login =true;
            }
            else
            {
                // NO LOGIN
                $login =false;
            }
        } catch (\Throwable $th) {
            // NO LOGIN
            $login =false;
        }
        //SI SE LOGRÓ REALIZAR EL LOGIN ENTONCES HACER TOKENS
        if ($login==true) {
            // generar token
            // $tokenResult = $admin->createToken("Docente");
            // $tokenResult = $admin->createToken('Docente', ['*'], now()->addMinutes(60));
            $NomC = $sesion->apellidos.' '.$sesion->nombres;
            $tokenResult = $sesion->createPersonalizedToken('Admin', ['*'], now()->addMinutes(60), ['nombrecompleto' => $NomC]);
            $token = $tokenResult->plainTextToken;

            // responder
            return response()->json([
                "access_token" => $token,
                "token_type" => "Bearer",
                "usuario" => $sesion
            ]);
        } else {
            return response()->json([
                "message" => "Nombre de usuario o contraseña incorrectos."
            ], 401);
        }
    }


    public function logout(Request $request)
    {
        $token = $request->input('token');
        $tokenParts = explode('|', $token);
        $tokenId = $tokenParts[0] ?? null;

        if ($tokenId) {
            // Usar DB::delete con vinculaciones de parámetros
            $deleted = DB::delete('DELETE FROM personal_access_tokens WHERE id = ?', [$tokenId]);

            if ($deleted) {
                return response()->json(['message' => 'Token DB ELIMINADO'], 200);
            } else {
                return response()->json(['message' => 'Token no encontrado o no eliminado'], 404);
            }
        }

        return response()->json(['message' => 'TOKEN DB NO ENCONTRADO'], 404);
    }

}
