<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Deudor;
use App\Models\GestionesDeudores;
use App\Models\Operacion;
use App\Models\Producto;
use App\Models\Propuesta;
use App\Models\Telefono;
use Illuminate\Http\Request;

class DeudorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * 
     */

    public function index()
    {
        $operaciones = Operacion::where('situacion', 1)
                        ->orderBy('created_at', 'desc')->get();
        $casosActivos = Operacion::where('situacion', 1)->count();
        $totalDNI = Operacion::distinct('nro_doc')->count();
        $deudaTotal = Operacion::sum('deuda_total');
        $deudaActiva = Operacion::where('situacion', 1)->sum('deuda_total');

        return view('deudores.cartera', [
            'operaciones'=>$operaciones,
            'casosActivos'=>$casosActivos,
            'totalDNI'=>$totalDNI,
            'deudaTotal'=>$deudaTotal,
            'deudaActiva'=>$deudaActiva,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Deudor $deudor)
    {
        $deudorId = $deudor->id;
        $estadoDeudor = GestionesDeudores::where('deudor_id', $deudorId)
                        ->latest('created_at')
                        ->first();

        if ($estadoDeudor) {
            $resultadoMasReciente = $estadoDeudor->resultado;
        } else {
            $resultadoMasReciente = 'Sin gestión';
        }

        $telefonos = Telefono::where('deudor_id', $deudorId)->get();   
        $operaciones = Operacion::where('deudor_id', $deudorId)->get();
        $propuestas = Propuesta::where('deudor_id', $deudorId)->get();
        
                
        return view('deudores.deudor-perfil', [
            'deudor'=>$deudor,
            'telefonos'=>$telefonos,
            'resultadoMasReciente'=>$resultadoMasReciente,
            'operaciones'=>$operaciones,
            'propuestas'=>$propuestas,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Deudor $deudor)
    {
        return view('deudores.deudor-nueva-gestion',[
            'deudor'=>$deudor,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function historial(Deudor $deudor)
    {
        $deudorId = $deudor->id;
        $deudorNombre=$deudor->nombre;
        $historiales = GestionesDeudores::where('deudor_id', $deudorId)
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('deudores.deudor-historial',[
            'deudorId'=>$deudorId,
            'deudorNombre'=>$deudorNombre,
            'historiales'=>$historiales,
        ]);
    }

    public function actualizarGestion(GestionesDeudores $gestionesDeudores)
    {
        $deudor_id = $gestionesDeudores->deudor_id;
        $deudor = Deudor::find($deudor_id);
        $deudorNombre = $deudor->nombre;

        return view('deudores.deudor-actualizar-gestion',[
            'gestionesDeudores' => $gestionesDeudores,
            'deudorNombre' => $deudorNombre,
        ]);
    }

    public function nuevoTelefono(Deudor $deudor)
    {
        $deudorId = $deudor->id;

        return view('deudores.deudor-nuevo-telefono',[
            'deudorId'=>$deudorId,
            'deudor'=>$deudor,
        ]);
    }

    public function actualizarTelefono(Telefono $telefono)
    {
        $deudor_id = $telefono->deudor_id;
        $deudor = Deudor::find($deudor_id);
        $deudorNombre = $deudor->nombre;

        return view('deudores.deudor-actualizar-telefono',[
            'telefono'=>$telefono,
            'deudorNombre' => $deudorNombre,
            'deudorNombre' => $deudorNombre,
            'deudor_id' => $deudor_id,
        ]);
    }

    public function propuesta(Operacion $operacion)
    {
        $deudorId = $operacion->deudor_id;
        $deudor = Deudor::find($deudorId);
        $deudorNombre = $deudor->nombre;
        $productoId = $operacion->producto_id;
        $producto = Producto::find($productoId);
        $honorarios = $producto->honorarios;
        

        return view('deudores.nueva-propuesta',[
            'deudorNombre' => $deudorNombre,
            'operacion' => $operacion,
            'honorarios' => $honorarios,
            'deudor' => $deudor,
            'deudorId' => $deudorId,
        ]);
    }

    public function historialPropuesta(Operacion $operacion)
    {
        $deudorId = $operacion->deudor_id;
        $deudor= Deudor::where('id', $deudorId)->first();
        $operacionId = $operacion->id;
        $propuestas = Propuesta::where('operacion_id', $operacionId)->orderBy('created_at', 'desc')->get();

        return view('deudores.historial-propuesta',[
            'deudorId' => $deudorId,
            'operacion' => $operacion,
            'operacionId' => $operacionId,
            'propuestas' => $propuestas,
            'deudor' => $deudor,
        ]);
    }

    public function propuestaIncobrable(Operacion $operacion)
    {
        $deudorId = $operacion->deudor_id;
        $deudor = Deudor::find($deudorId);
        $deudorNombre = $deudor->nombre;
        $productoId = $operacion->producto_id;
        $producto = Producto::find($productoId);
        $honorarios = $producto->honorarios;
        

        return view('deudores.propuesta-incobrable',[
            'deudorNombre' => $deudorNombre,
            'operacion' => $operacion,
            'honorarios' => $honorarios,
            'deudor' => $deudor,
            'deudorId' => $deudorId,
        ]);
    }
}
