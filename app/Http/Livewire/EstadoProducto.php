<?php

namespace App\Http\Livewire;

use App\Models\Producto;
use Livewire\Component;

class EstadoProducto extends Component
{
    public $producto;

    public function estadoProducto(Producto $producto)
    {
        if($producto->estado === 1)
        {
            $producto->estado = 2;
            $producto->usuario_ultima_modificacion_id =  auth()->id();

        } else {
            $producto->estado = 1;
            $producto->usuario_ultima_modificacion_id =  auth()->id();
        }
        
        $producto->save();
        $this->emit('estadoActualizado');
    }

    public function render()
    {
        return view('livewire.estado-producto');
    }
}
