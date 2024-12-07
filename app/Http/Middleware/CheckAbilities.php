<?php
namespace App\Http\Middleware;
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class CheckAbilities
{
    public function handle(Request $request, Closure $next, ...$abilities)
    {
        // Log::info('Middleware CheckAbilities iniciado.');

        // Obtener el token de la solicitud
        $token = $request->bearerToken();
        // Log::info('Token recibido: ' . $token);

        if ($token) {
            // Buscar el token en la base de datos
            $personalAccessToken = PersonalAccessToken::findToken($token);
            if ($personalAccessToken) {
                // Log::info('Token encontrado en la base de datos.');

                // Obtener el cliente asociado con el token
                $cliente = $personalAccessToken->tokenable; // Asumiendo que 'tokenable' es la relación correcta
                // Log::info('Cliente asociado con el token: ' . $cliente->id);

                if ($cliente) {
                    // Verificar las habilidades del token
                    foreach ($abilities as $ability) {
                        if (!$personalAccessToken->can($ability)) {
                            // Log::warning('Falta de habilidad: ' . $ability);
                            return response()->json(['message' => 'PERMISOS INSUFICIENTES.'], 403);
                        }
                    }

                    // // Log::info('Middleware CheckAbilities finalizado.');
                    return $next($request);
                }
            } else {
                // Log::warning('Token no encontrado en la base de datos.');
            }
        } else {
            // Log::warning('Token no recibido en la solicitud.');
        }

        // Log::warning('Acceso no autorizado.');
        return response()->json(['message' => 'TOKEN EXPIRADO DESDE VERIFICACION DE HABILIDADES.'], 401);
    }
}

