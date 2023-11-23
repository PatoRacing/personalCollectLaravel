<?php

namespace App\Http\Livewire;

use App\Models\Cliente;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Livewire\Component;

class CrearCliente extends Component
{
    public $nombre;
    public $contacto;
    public $telefono;
    public $email;
    public $localidad;
    public $codigo_postal;
    public $provincia;
    public $usuarioNombre;
    public $usuario_ultima_modificacion_id;

    protected $rules = [
        'nombre'=> 'required',
        'contacto'=> 'required',
        'telefono'=> 'required',
        'email'=> 'required',
        'localidad'=> 'required',
        'codigo_postal'=> 'required',
        'provincia'=> 'required',
    ];

    public function crearCliente()
    {
        $datos = $this->validate();

        Cliente::create([
        'nombre'=> $datos['nombre'],
        'contacto'=>$datos['contacto'],
        'telefono'=>$datos['telefono'],
        'email'=>$datos['email'],
        'localidad'=>$datos['localidad'],
        'codigo_postal'=>$datos['codigo_postal'],
        'provincia'=>$datos['provincia'],
        'estado'=> 1,
        'creado'=>now(),
        'usuario_ultima_modificacion_id'=>auth()->id(),
        'fecha_ultima_modificacion'=>now(),
        ]);
        
        return redirect('clientes')->with('message', 'Cliente agregado correctamente');
    }

    public function render()
    {
        $usuario = Auth::user();
        $this->usuarioNombre = $usuario->name . " " . $usuario->apellido;
        return view('livewire.crear-cliente');
    }
}
