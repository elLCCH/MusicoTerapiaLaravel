<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Routing\Controller;
use App\Http\Middleware\UpdateTokenExpiration;

class DatabaseQueryController extends Controller
{
    
    public function __construct()
    {
        $this->middleware(['auth:sanctum', UpdateTokenExpiration::class]);
    }
    public function select(Request $request)
    {
        $validated = $request->validate([
            'sql' => ['required', 'string'],
            'bindings' => ['sometimes', 'array'],
            'maxRows' => ['sometimes', 'integer', 'min:1', 'max:1000'],
        ]);

        $sql = trim($validated['sql']);
        $bindings = $validated['bindings'] ?? [];
        $maxRows = $validated['maxRows'] ?? 200;

        // Solo consultas de lectura (SELECT)
        if (!preg_match('/^\s*select\b/i', $sql)) {
            return response()->json([
                'ok' => false,
                'message' => 'Solo se permiten consultas SELECT (solo lectura).'
            ], 422);
        }

        // Bloquear multi-statement y comentarios comunes
        if (preg_match('/;|--|\/\*|\*\/|#/', $sql)) {
            return response()->json([
                'ok' => false,
                'message' => 'Consulta rechazada: no se permiten ";" ni comentarios.'
            ], 422);
        }

        // Bloqueo de keywords peligrosas (defensa adicional)
        $forbiddenPatterns = [
            '/\bdelete\b/i',
            '/\bdrop\b/i',
            '/\balter\b/i',
            '/\btruncate\b/i',
            '/\bcreate\b/i',
            '/\breplace\b/i',
            '/\brename\b/i',
            '/\bgrant\b/i',
            '/\brevoke\b/i',
            '/\block\b/i',
            '/\bunlock\b/i',
            '/\bcall\b/i',
            '/\bprocedure\b/i',
            '/\bfunction\b/i',
            '/\binto\s+outfile\b/i',
            '/\binto\s+dumpfile\b/i',
            '/\bload_file\s*\(/i',
            '/\bsleep\s*\(/i',
            '/\bbenchmark\s*\(/i',
            '/\bfor\s+update\b/i',
            '/\block\s+in\s+share\s+mode\b/i',
        ];

        foreach ($forbiddenPatterns as $pattern) {
            if (preg_match($pattern, $sql)) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Consulta rechazada por contener operaciones no permitidas.'
                ], 422);
            }
        }

        // Envolver para aplicar límite duro de filas
        $limitedSql = "select * from ($sql) as _q limit " . (int) $maxRows;

        try {
            $rows = DB::select($limitedSql, $bindings);

            return response()->json([
                'ok' => true,
                'count' => count($rows),
                'data' => $rows,
            ]);
        } catch (QueryException $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error SQL: ' . $e->getMessage(),
            ], 422);
        }
    }
}
