<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use Illuminate\Http\Request;
use App\Http\Middleware\UpdateTokenExpiration;
use Illuminate\Routing\Controller;

class PagoController extends Controller
{
    public function __construct() {
        $this->middleware(UpdateTokenExpiration::class);
    }
    //controllerPHPlcch Pago, $
    //#region Inicio Controller de Crud PHP de Pago
    public function index()
    {

        // $Pago = Pago::with('ciclos') //ANTES
        $Pago = Pago::with(['ciclos' => function ($query) { //ACTUALMENTE PARA ORDENAR POR SESION DE LOS CICLOS
            $query->orderBy('sesion', 'asc'); // Ordenar ciclos por sesión de forma ascendente
        }])
        ->join('infoclientes', 'pagos.id_infocliente', '=', 'infoclientes.id')
        ->join('clientes', 'infoclientes.id_cliente', '=', 'clientes.id')
        ->addSelect('pagos.*', 'clientes.nombres', 'clientes.apellidos', 'clientes.celular', 'clientes.carnet', 'clientes.estado','infoclientes.fechaadmision')
        ->orderBy('clientes.estado', 'asc')
        ->orderBy('clientes.apellidos', 'asc')
        ->orderBy('clientes.nombres', 'asc')
        ->orderBy('infoclientes.fechaadmision', 'desc')
        ->orderBy('pagos.id', 'asc')->get();
        return response()->json(['data' => $Pago]);
    }
    // public function index()
    // {
    //     $InfoCliente = InfoCliente::join('clientes', 'infoclientes.id_cliente', '=', 'clientes.id')
    //         ->select(
    //             'infoclientes.*',
    //             'clientes.nombres',
    //             'clientes.apellidos',
    //             'clientes.celular'
    //         )
    //         ->get();

    //     return response()->json(['data' => $InfoCliente]);
    // }

    public function store(Request $request)
    {
        $Pago = $request->all();
        // $Pago = Pago::insert($Pago);
        $Pago = Pago::create($Pago);
        return response()->json(['data' => $Pago]);
    }

    public function show($id)
    {
        $Pago = Pago::where('id','=',$id)->firstOrFail();
        return response()->json(['data' => $Pago]);
    }


    public function update(Request $request)
    {
        $Pago = $request->all();
        Pago::where('id','=',$request->id)->update($Pago);
        return response()->json(['data' => $Pago]);
    }

    public function destroy($id)
    {
        Pago::destroy($id);
        return response()->json(['data' => 'ELIMINADO EXITOSAMENTE']);
    }
    //#endregion Fin Controller de Crud PHP de Pago

    public function AllPagosidCliente($id) {
        $Pago = Pago::with(['ciclos' => function ($query) {
            $query->orderBy('sesion', 'asc');
        }])
        ->join('infoclientes', 'pagos.id_infocliente', '=', 'infoclientes.id')
        ->join('clientes', 'infoclientes.id_cliente', '=', 'clientes.id')
        ->addSelect('pagos.*', 'clientes.nombres', 'clientes.apellidos', 'clientes.celular', 'clientes.carnet', 'clientes.estado', 'infoclientes.fechaadmision')
        ->where('clientes.id', '=', $id)
        ->orderBy('clientes.estado', 'asc')
        ->orderBy('clientes.apellidos', 'asc')
        ->orderBy('clientes.nombres', 'asc')
        ->orderBy('infoclientes.fechaadmision', 'desc')
        ->orderBy('pagos.id', 'desc')
        ->get();
        return response()->json(['data' => $Pago]);
    }
}
