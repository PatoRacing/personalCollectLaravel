<?php

namespace App\Http\Livewire;

use App\Models\Cliente;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ActualizarCliente extends Component
{
    public $cliente_id;
    public $nombre;
    public $contacto;
    public $telefono;
    public $email;
    public $localidad;
    public $codigo_postal;
    public $provincia;
    public $usuarioNombre;
    public $usuario_ultima_modificacion_id;
    public $fecha_ultima_modificacion;

    protected $rules = [
        'nombre'=> 'required',
        'contacto'=> 'required',
        'telefono'=> 'required',
        'email'=> 'required',
        'localidad'=> 'required',
        'codigo_postal'=> 'required',
        'provincia'=> 'required',
    ];

    public function mount(Cliente $cliente)
    {
        $this->cliente_id = $cliente->id;
        $this->nombre = $cliente->nombre;
        $this->contacto = $cliente->contacto;
        $this->telefono = $cliente->telefono;
        $this->email = $cliente->email;
        $this->localidad = $cliente->localidad;
        $this->codigo_postal = $cliente->codigo_postal;
        $this->provincia = $cliente->provincia;
    }

    public function editarCliente()
    {
        $datos = $this->validate();
        //Cliente a editar
        $cliente = Cliente::find($this->cliente_id);
        //Asignar los valores
        $cliente->nombre = $datos['nombre'];
        $cliente->contacto = $datos['contacto'];
        $cliente->telefono = $datos['telefono'];
        $cliente->email = $datos['email'];
        $cliente->localidad = $datos['localidad'];
        $cliente->codigo_postal = $datos['codigo_postal'];
        $cliente->provincia = $datos['provincia'];
        $cliente->usuario_ultima_modificacion_id = auth()->id();
        $cliente->fecha_ultima_modificacion = now();

        $cliente->save();

        return redirect('clientes')->with('message', 'Cliente actualizado correctamente');

    }
    
    public function render()
    {
        $usuario = Auth::user();
        $this->usuarioNombre = $usuario->name . " " . $usuario->apellido;
        return view('livewire.actualizar-cliente',);
    }
}
