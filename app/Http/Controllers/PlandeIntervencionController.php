<?php

namespace App\Http\Controllers;

use App\Models\PlandeIntervencion;
use Illuminate\Http\Request;
use App\Http\Middleware\UpdateTokenExpiration;
use Illuminate\Routing\Controller;

class PlandeIntervencionController extends Controller
{
    public function __construct() {
        $this->middleware(UpdateTokenExpiration::class);
    }
    //#region Inicio Controller de Crud PHP de PlandeIntervencion
    public function index()
    {
        // $PlandeIntervencion = PlandeIntervencion::all();
        $PlandeIntervencion = PlandeIntervencion::with('subplandeintervencion')
        ->join('infoclientes', 'plandeintervencions.id_infocliente', '=', 'infoclientes.id')
        ->join('clientes', 'infoclientes.id_cliente', '=', 'clientes.id')
        ->addSelect('plandeintervencions.*', 'clientes.nombres', 'clientes.apellidos', 'clientes.celular', 'clientes.carnet')->get();
        return response()->json(['data' => $PlandeIntervencion]);
    }


    public function store(Request $request)
    {
        $PlandeIntervencion = $request->all();
        PlandeIntervencion::insert($PlandeIntervencion);
        return response()->json(['data' => $PlandeIntervencion]);
    }

    public function show($id)
    {
        $PlandeIntervencion = PlandeIntervencion::where('id','=',$id)->firstOrFail();
        return response()->json(['data' => $PlandeIntervencion]);
    }


    public function update(Request $request)
    {
        $PlandeIntervencion = $request->all();
        PlandeIntervencion::where('id','=',$request->id)->update($PlandeIntervencion);
        return response()->json(['data' => $PlandeIntervencion]);
    }

    public function destroy($id)
    {
        PlandeIntervencion::destroy($id);
        return response()->json(['data' => 'ELIMINADO EXITOSAMENTE']);
    }
    //#endregion Fin Controller de Crud PHP de PlandeIntervencion
    public function CargarPlandeIntervencionxidInfoCliente($id)
    {
        // $PlandeIntervencion = PlandeIntervencion::all();
        $PlandeIntervencion = PlandeIntervencion::with('subplandeintervencion')
        ->join('infoclientes', 'plandeintervencions.id_infocliente', '=', 'infoclientes.id')
        ->join('clientes', 'infoclientes.id_cliente', '=', 'clientes.id')
        ->addSelect('plandeintervencions.*', 'clientes.nombres', 'clientes.apellidos', 'clientes.celular', 'clientes.carnet')
        ->where('id_infocliente','=',$id)->get();
        return response()->json(['data' => $PlandeIntervencion]);
    }
}
