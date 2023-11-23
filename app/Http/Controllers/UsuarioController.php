<?php

namespace App\Http\Controllers;

use App\Models\EstadoDeUsuario;
use App\Models\Rol;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index()
    {
        $usuarios = User::orderBy('created_at', 'desc')->get();
        

        return view('usuarios.index', [
            'usuarios'=>$usuarios
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
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'dni' => ['required', 'string', 'max:12'],
            'rol_id' => ['required', 'string', 'max:12'],
            'telefono' => ['required', 'string', 'regex:/^[0-9]+$/', 'max:20'],
            'domicilio' => ['required', 'string', 'max:255'],
            'localidad' => ['required', 'string', 'max:255'],
            'codigo_postal' => ['required', 'string', 'max:20'],
            'fecha_de_ingreso' => ['required', 'date'],
            'email' => ['required', 'string', 'email', 'max:255', \Illuminate\Validation\Rule::unique('users')->ignore($request->id),],            
        ]);

        $usuario = User::findOrFail($request->id);
        $usuario->update($request->all());
        return redirect('usuario')->with('message', 'Usuario actualizado correctamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $usuario = User::find($id);
        $roles = Rol::all();
        $fechaIngreso = Carbon::parse($usuario->fecha_de_ingreso)->toDateString();
        $usuarioAutenticado = auth()->id();

        return view('usuarios.actualizar-usuario', [
            "usuario"=> $usuario,
            "roles"=> $roles,
            "usuarioAutenticado"=> $usuarioAutenticado,
            "fechaIngreso"=> $fechaIngreso
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
}
