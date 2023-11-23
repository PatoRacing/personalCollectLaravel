<?php

namespace App\Http\Livewire;

use App\Models\Cliente;
use App\Models\Producto;
use Livewire\Component;

class ProductoNombre extends Component
{
    public $producto;
    public $index;

    public function productoNombre(Producto $producto) 
    {
        $cliente = Cliente::find($producto->cliente_id);

        $this->emit('modalProducto', [
            'nombre' => $producto->nombre,
            'cliente_nombre' => $cliente->nombre,
            'honorarios' => $producto->honorarios,
            'comision_cliente' => $producto->comision_cliente,
            'id' => $producto->id,
        ]);
    }
    
    public function render()
    {
        return view('livewire.producto-nombre');
    }
}
