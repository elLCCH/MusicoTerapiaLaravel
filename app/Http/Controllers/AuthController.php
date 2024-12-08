<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $admin = Cliente::where('usuario', '=', $user)->where('contrasenia', '=', $pass)->first();

        if ($admin) {
            // generar token
            // $tokenResult = $admin->createToken("Docente");
            // $tokenResult = $admin->createToken('Docente', ['*'], now()->addMinutes(60));
            $NomC = $admin->apellidos.' '.$admin->nombres;
            $tokenResult = $admin->createPersonalizedToken('Admin', ['view-cliente'], now()->addMinutes(60), ['nombrecompleto' => $NomC]);
            $token = $tokenResult->plainTextToken;

            // responder
            return response()->json([
                "access_token" => $token,
                "token_type" => "Bearer",
                "usuario" => $admin
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
