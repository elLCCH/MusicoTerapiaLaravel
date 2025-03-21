<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class FileUploadController extends Controller
{
    public function uploadFile(Request $request)
    {
        $file = $request->file('file');
        $path = '';
        // $fileName = time() . '_' . $file->getClientOriginalName();
        $fileName = time() . '' . $file->getClientOriginalName();

        switch ($request->input('type')) {
            case 'GALERIA':
                $path = 'ArchivosCrud/GaleriasFiles';
                break;
            case 'CAROUSEL':
                $path = 'ArchivosCrud/CarouselesFiles';
                break;
            case 'ARCHIVOSPAGO':
                $path = 'ArchivosCrud/ArchivosPagosFiles';
                break;
            case 'CLIENTE':
                $path = 'ArchivosCrud/ClientesFiles';
                break;
            case 'USUARIO':
                $path = 'ArchivosCrud/UsuariosFiles';
                break;
            case 'SERVICIO':
                $path = 'ArchivosCrud/ServiciosFiles';
                break;
            default:
                return response()->json(['error' => 'Tipo de archivo no válido'], 400);
        }

        $file->move(public_path($path), $fileName);

        return response()->json(['filePath' => "$path/$fileName"], 200);
    }
    public function deleteFile(Request $request)
    {
        $filePath = $request->input('filePath');
        if (File::exists(public_path($filePath))) {
            File::delete(public_path($filePath));
            return response()->json(['success' => true, 'message' => 'File deleted successfully']);
        } else {
            return response()->json(['success' => false, 'message' => 'File not found']);
        }
    }
}

