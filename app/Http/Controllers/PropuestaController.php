<?php

namespace App\Http\Controllers;

use App\Exports\PropuestasExport;
use App\Models\Deudor;
use App\Models\Operacion;
use App\Models\Producto;
use App\Models\Propuesta;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PropuestaController extends Controller
{
    public function index(Propuesta $propuesta)
    {
        $propuestas = Propuesta::where('estado', 'Propuesta de Pago')->get();

        return view('propuestas.propuestas',[
            'propuestas'=>$propuestas
        ]);
    }

    public function exportar (Propuesta $propuesta)
    {
        return Excel::download(new PropuestasExport, 'propuestas.xlsx');

        return view('propuestas.propuestas');
    }
}
