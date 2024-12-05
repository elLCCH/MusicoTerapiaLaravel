<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    // public function funLogin(Request $request){


    //     $validado = Validator::make($request->all(), [
    //         'usuario' => ['required'],
    //         'contrasenia' => ['required'],
    //     ]);

    //     if($validado->fails()){
    //         return response()->json(["errors" => $validado->errors()], 422);
    //     }


    //     // if(!Auth::attempt(["email" => $request->email, "password" => $request->password])){
    //     //     return response()->json(["mensaje" => "Credenciales Incorrectas"], 401);
    //     // }
    //     $user = $request->input('usuario');
    //     $pass = $request->input('contrasenia');
    //     $admin = Cliente::where('usuario', '=', $user)->where('contrasenia', '=', $pass)->first();

    //     if ($admin) {
    //         // generar token
    //         $token = $request->user()->createToken("Token Login")->plainTextToken;

    //         return response()->json([
    //             "access_token" => $token,
    //             "usuario" => $request->user()
    //         ], 201);
    //     }else{
    //         return response()->json(["mensaje"=>"no se encontro nada"],201);
    //     }

    // }
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
            $tokenResult = $admin->createToken("login");
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

    // public function registro(Request $request)
    // {
    //     // validar
    //     $request->validate([
    //         "name" => "required",
    //         "email" => "required|email|unique:users",
    //         "password" => "required",
    //         "c_password" => "required|same:password"
    //     ]);
    //     // registro
    //     $usuario = new User();
    //     $usuario->name = $request->name;
    //     $usuario->email = $request->email;
    //     $usuario->password = bcrypt($request->password);
    //     $usuario->save();

    //     // responder

    //     return response()->json(["mensaje" => "Usuario Registrado"], 201);
    // }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            "mensaje" => "Logout"
        ]);

    }

    public function perfil(Request $request)
    {
        return response()->json($request->user());
    }
}
