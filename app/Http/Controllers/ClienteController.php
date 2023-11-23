<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Imports\DeudorImport;
use App\Imports\OperacionImport;
use App\Imports\SituacionImport;
use App\Imports\SituacionOperacionImport;
use App\Models\Deudor;
use App\Models\Operacion;
use App\Models\SituacionesOperaciones;
use Carbon\Carbon;
use GuzzleHttp\Psr7\Request;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clientes = Cliente::orderBy('creado', 'desc')->get();
        
        return view('clientes.clientes', [
            'clientes'=>$clientes,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('clientes.crear-cliente');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreClienteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreClienteRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show(Cliente $cliente)
    {
        $operaciones = $cliente->operaciones()->orderBy('created_at', 'desc')->get();
        $totalCasos = $cliente->operaciones->count();
        $casosActivos = $cliente->operaciones->where('situacion', 1)->count();
        $totalDNI = $cliente->operaciones->unique('nro_doc')->count();
        $deudaTotal = $cliente->operaciones->sum('deuda_total');
        $deudaActiva = $cliente->operaciones->where('situacion', 1)->sum('deuda_total');

        return view('clientes.perfil-cliente', [
            'cliente'=>$cliente,
            'operaciones'=>$operaciones,
            'totalCasos'=>$totalCasos,
            'casosActivos'=>$casosActivos,
            'totalDNI'=>$totalDNI,
            'deudaTotal'=>$deudaTotal,
            'deudaActiva'=>$deudaActiva,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function edit(Cliente $cliente)
    {
        return view('clientes.actualizar-cliente', [
            'cliente'=>$cliente,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateClienteRequest  $request
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateClienteRequest $request, Cliente $cliente)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cliente $cliente)
    {
        //
    }

    public function importar(Cliente $cliente)
    {
        $usuarioAutenticado = auth()->user();
        if ($usuarioAutenticado) {
            $nombreUsuario = $usuarioAutenticado->name . " " . $usuarioAutenticado->apellido;
        }

        return view('clientes.importar-cartera-cliente', [
            'cliente'=>$cliente,
            'nombreUsuario'=>$nombreUsuario
        ]);
    }

    public function almacenar(Cliente $cliente, HttpRequest $request)
    {   
        //Validamos el excel
        $request->validate([
            'importar'=> 'required|mimes:xls,xlsx|max:2048'
        ]);
        
        $operaciones = Operacion::all();
        $errorImport = null;
        //Se inicia la importacion
        try {
            DB::beginTransaction();
            $excel = $request->file('importar');

            //Se importan los deudores
            $importacionDeudor = new DeudorImport;
            Excel::import($importacionDeudor, $excel);

            //Se Importan las operaciones
            $clienteId = $request->input('cliente_id');
            $cliente = Cliente::find($clienteId);
            $importacionOperacion = new OperacionImport($clienteId, $operaciones, $cliente);
            Excel::import($importacionOperacion, $excel);

            //Contar cuantas operaciones se crearon y cuantas se actualizaron
            $operacionesActualizadas = $importacionOperacion->getOperacionesActualizadas();
            $operacionesCreadas = $importacionOperacion->getOperacionesCreadas();

            //Error: el cliente no tiene asignado el producto
            $errorImport = $importacionOperacion->getError();
            if ($errorImport) {
                throw new \Exception($errorImport);
            }

            //Se importan solo las Situaciones Operaciones
            $clienteId = $request->input('cliente_id');
            $importacionSituacionOperacion = new SituacionOperacionImport($clienteId);
            Excel::import($importacionSituacionOperacion, $excel);

            //Activar o desactiva operaciones
                // 1- Obtener valores únicos de 'operacion' en ambas tablas
                $situacionesOperacionesBD = SituacionesOperaciones::where('cliente_id', $clienteId)->pluck('operacion')->toArray();
                $operacionesBD = Operacion::where('cliente_id', $clienteId)->pluck('operacion')->toArray();
                // 2- Encontrar las diferencias
                $operacionesSinSituacion = array_diff($operacionesBD, $situacionesOperacionesBD);
                // Contador para operaciones con situación cambiada
                $operacionesConSituacionCambiada = 0;
                // 3- Actualizar la columna 'situacion' en 'operaciones'
                foreach ($operacionesSinSituacion as $operacionSinSituacion) {
                    $operacion = Operacion::where('operacion', $operacionSinSituacion)->where('cliente_id', $clienteId)->first();
                    
                    if ($operacion && $operacion->situacion != 2) {
                        // Verificar si la situación cambia a 2 antes de actualizar
                        $operacion->update(['situacion' => 2]);
                        $operacionesConSituacionCambiada++;
                    }
                }
            //Si algo falla en la importacion se deshace todo
            DB::commit();
            SituacionesOperaciones::truncate();

        } catch(\Exception $e) {
            DB::rollBack();
            //Envio de mensaje de error
            $message = 'Ocurrió un error y se cancelo la importación: ' ;
            if ($errorImport) {
                $message .= ' ' . $errorImport;
            } else {
                $message .= $e->getMessage();
            }
        
            return redirect()->route('importar.cartera.cliente', ['cliente' => $cliente->id])->withErrors(['importar' => $message]);
        }

        $message = "Se crearon {$importacionDeudor->creados} nuevos deudores.<hr>
                    Se omiten {$importacionDeudor->omitidosExcel} deudrores debido a que estaban repetidos en el excel.<hr>
                    Se omiten {$importacionDeudor->omitidosDB} deudores debido a que ya existían en la Base de Datos.<hr>
                    Se crearon {$operacionesCreadas} nuevas operaciones correspondientes a este cliente.<hr>
                    Se actualizaron {$operacionesActualizadas} operaciones que ya pertenecían a este cliente.<hr>
                    Se desactivaron {$operacionesConSituacionCambiada} operaciones del cliente ya que no figuran en la actual importacion.
                    ";

        return redirect()->route('perfil.cliente', ['cliente' => $cliente->id])->with('message', $message);
    }
}
