<?php

namespace App\Http\Livewire;

use App\Models\Cliente;
use App\Models\Producto;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CrearProducto extends Component
{
    public $nombre;
    public $honorarios;
    public $comision_cliente;
    public $clientes;
    public $cliente_id;
    public $usuarioNombre;
    public $usuario_ultima_modificacion_id;

    protected $rules = [
        'nombre'=> 'required',
        'honorarios'=> 'required',
        'comision_cliente'=> 'required',
        'cliente_id'=> 'required'
    ];

    public function crearProducto()
    {
        $datos = $this->validate();

        Producto::create([
        'nombre'=> $datos['nombre'],
        'honorarios'=>$datos['honorarios'],
        'comision_cliente'=>$datos['comision_cliente'],
        'cliente_id'=>$datos['cliente_id'],
        'estado'=> 1,
        'usuario_ultima_modificacion_id'=>auth()->id(),
        ]);
        
        return redirect('productos')->with('message', 'Producto agregado correctamente');
    }
    

    public function render()
    {

        $usuario = Auth::user();
        $this->usuarioNombre = $usuario->name . " " . $usuario->apellido;

        return view('livewire.crear-producto');
    }
}
