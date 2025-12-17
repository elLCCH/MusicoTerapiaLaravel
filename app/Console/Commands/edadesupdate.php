<?php

namespace App\Console\Commands;

use Carbon\Carbon;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class edadesupdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:edadesupdate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        
        // //ACTUALIZAR EDADES DE LOS clientes
        // DB::select("UPDATE clientes e set e.edad= TIMESTAMPDIFF(YEAR,e.fechnac,CURDATE())");
        // $texto = "[".date("Y-m-d H:i:s")."]:SE ACTUALIZARON LAS EDADES";
        // Storage::append("archivo.txt",$texto);
        $clientes = DB::table('clientes')->select('id', 'fechnac')->get();

        foreach ($clientes as $cliente) {
            $nacimiento = Carbon::parse($cliente->fechnac);
            $hoy = Carbon::now();

            $edad = $nacimiento->diff($hoy);

            // Formato: "X años y Y meses"
            $edadTexto = "{$edad->y} años y {$edad->m} meses";

            // Actualizar en la base de datos
            DB::table('clientes')->where('id', $cliente->id)->update([
                'edad' => $edadTexto
            ]);
        }

        $texto = "[" . date("Y-m-d H:i:s") . "]: SE ACTUALIZARON LAS EDADES EN FORMATO AÑOS Y MESES";
        Storage::append("archivo.txt", $texto);
    }
}
