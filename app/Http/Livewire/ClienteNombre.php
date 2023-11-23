<?php

namespace App\Http\Livewire;

use App\Models\Cliente;
use Carbon\Carbon;
use Livewire\Component;

class ClienteNombre extends Component
{
    public $cliente;
    public $index;

    public function clienteNombre(Cliente $cliente)
    {
        $this->emit('modalCliente', $cliente);
    }

    public function render()
    {
        return view('livewire.cliente-nombre',[]);
    }
}
