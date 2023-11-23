<?php

namespace App\Http\Livewire;

use App\Models\Producto;
use Livewire\Component;

class ActualizarProducto extends Component
{
    public $producto_id;
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

    public function mount(Producto $producto)
    {
        $this->producto_id = $producto->id;
        $this->nombre = $producto->nombre;
        $this->honorarios = $producto->honorarios;
        $this->comision_cliente = $producto->comision_cliente;
        $this->cliente_id = $producto->cliente_id;
    }

    public function actualizarProducto()
    {
        $datos = $this->validate();
        $producto = Producto::find($this->producto_id);

        $producto->nombre = $datos['nombre'];
        $producto->honorarios = $datos['honorarios'];
        $producto->comision_cliente = $datos['comision_cliente'];
        $producto->cliente_id = $datos['cliente_id'];
        $producto->usuario_ultima_modificacion_id = auth()->id();

        $producto->save();

        return redirect('productos')->with('message', 'Producto actualizado correctamente');
    }

    public function render()
    {
        return view('livewire.actualizar-producto');
    }
}
