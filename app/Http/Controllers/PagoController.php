<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use Illuminate\Http\Request;
use App\Http\Middleware\UpdateTokenExpiration;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

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


    public function AllPagosidinfoCliente($idinfocliente) {
        $Pago = Pago::with(['ciclos' => function ($query) {
            $query->orderBy('sesion', 'asc');
        }])
        ->join('infoclientes', 'pagos.id_infocliente', '=', 'infoclientes.id')
        ->join('clientes', 'infoclientes.id_cliente', '=', 'clientes.id')
        ->addSelect('pagos.*', 'clientes.nombres', 'clientes.apellidos', 'clientes.celular', 'clientes.carnet', 'clientes.estado', 'infoclientes.fechaadmision')
        ->where('infoclientes.id', '=', $idinfocliente)
        ->orderBy('clientes.estado', 'asc')
        ->orderBy('clientes.apellidos', 'asc')
        ->orderBy('clientes.nombres', 'asc')
        ->orderBy('infoclientes.fechaadmision', 'desc')
        ->orderBy('pagos.id', 'desc')
        ->get();
        return response()->json(['data' => $Pago]);
    }
    //PARA LOS PAGOS EXCEL
    public function clientesActivosPagos()
    {
        $data = DB::select("
            SELECT
                ic.fechaadmision,
                c.estado,
                ci.ciclos,
                c.apellidos  AS cliente_apellidos,
                c.nombres    AS cliente_nombres,
                ic.diagnostico,
                ic.tipotratamiento,
                ic.duracion,
                ic.frecuencia,
                p.horario,
                p.tipo       AS tipo_pago,
                p.precio,
                p.saldo,
                p.pagado,
                p.descuento,
                ap.fechapago,
                ap.horapago,
                ap.monto,
                ap.estadopago
            FROM clientes c
            INNER JOIN infoclientes ic ON ic.id_cliente = c.id
            INNER JOIN pagos p ON p.id_infocliente = ic.id
            LEFT JOIN archivospagos ap ON ap.id_pago = p.id
            LEFT JOIN (
                SELECT
                    id_pago,
                    GROUP_CONCAT(DISTINCT nrociclo ORDER BY nrociclo SEPARATOR ', ') AS ciclos
                FROM ciclos
                GROUP BY id_pago
            ) ci ON ci.id_pago = p.id
            WHERE c.estado = 'ACTIVO'
            ORDER BY c.apellidos, c.nombres, ic.fechaadmision, ci.ciclos, ap.fechapago
        ");

        return response()->json($data);
    }
}
