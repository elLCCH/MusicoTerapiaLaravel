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
        $esadmin = false;
        // $admin = Cliente::where('usuario', '=', $user)->where('contrasenia', '=', $pass)->first();
        // $admin = Usuario::where('usuario', '=', $user)->where('contrasenia', '=', $pass)->first();

        //INTENTANDO INICIO DE SESION COMO ADMINISTRADOR
        $sesion = Usuario::where('usuario','=', $user)->first();
        try {
            if (Hash::check($pass, $sesion->contrasenia)) {
                // INICIO DE SESION CORRECTO COMO ADMINISTRADOR
                $login =true;
                $esadmin = true; // es un administrador
            }
            else
            {
               $login = false;
            }
        } catch (\Throwable $th) {
            // NO LOGIN COMO ADMINISTRADOR
            $login =false;
        }


        //INTENTANDO INICIO DE SESION COMO CLIENTE
        if ($login==false) {
            //INTENTANDO INICIO DE SESION COMO CLIENTE
            $sesion = Cliente::where('usuario','=', $user)->first();
            try {
                if (Hash::check($pass, $sesion->contrasenia)) {
                    // INICIO DE SESION CORRECTO COMO CLIENTE
                    $login =true;
                    $esadmin = false; // es un cliente
                }
                else
                {
                   $login = false;
                }
            } catch (\Throwable $th) {
                // NO LOGIN COMO CLIENTE
                $login =false;
            }
        }



        //SI SE LOGRÓ REALIZAR EL LOGIN ENTONCES HACER TOKENS
        if ($login==true && $esadmin==true) {
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
        } elseif ($login==true && $esadmin==false) {
            //INICIANDO SESION COMO CLIENTE
            // generar token
            $NomC = $sesion->apellidos.' '.$sesion->nombres;
            $tokenResult = $sesion->createPersonalizedToken('Cliente', ['Clientela'], now()->addMinutes(60), ['nombrecompleto' => $NomC]);
            $token = $tokenResult->plainTextToken;
            // responder
            return response()->json([
                "access_token" => $token,
                "token_type" => "Bearer",
                "usuario" => $sesion
            ]);
        }
        else {
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

    // Esta función obtiene el usuario autenticado, ya sea Usuario o Cliente
    public function getUser(Request $request)
    {
        $user = $request->user();
        $token = $request->bearerToken();

        // Obtener abilities del token
        $abilities = [];
        if ($token) {
            $tokenParts = explode('|', $token);
            $tokenId = $tokenParts[0] ?? null;
            if ($tokenId) {
            $tokenRecord = DB::table('personal_access_tokens')->where('id', $tokenId)->first();
            if ($tokenRecord && isset($tokenRecord->abilities)) {
                $abilities = json_decode($tokenRecord->abilities, true) ?? [];
            }
            }
        }

        // Verifica si el usuario autenticado es del modelo Usuario o Cliente
        if ($user instanceof \App\Models\Usuario) {
            return response()->json([
            'tipo' => 'admin',
            'usuario' => $user,
            'abilities' => $abilities
            ]);
        } elseif ($user instanceof \App\Models\Cliente) {
            return response()->json([
            'tipo' => 'cliente',
            'usuario' => $user,
            'abilities' => $abilities
            ]);
        } else {
            return response()->json([
            'message' => 'No autenticado.'
            ], 401);
        }
    }

    public function cambiarClave(Request $request)
    {
        $request->validate([
            'claveActual' => 'required',
            'nuevaClave' => 'required|min:6',
        ]);

        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'No autenticado.'], 401);
        }

        // Verificar clave actual
        if (!Hash::check($request->input('claveActual'), $user->contrasenia)) {
            return response()->json(['message' => 'La clave actual es incorrecta.'], 400);
        }

        // Actualizar la contraseña
        $user->contrasenia = Hash::make($request->input('nuevaClave'));
        $user->save();

        return response()->json(['message' => 'Contraseña actualizada correctamente.']);
    }

    

}
