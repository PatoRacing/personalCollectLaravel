<?php

namespace App\Http\Livewire;

use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;

class UsuarioModal extends Component
{

    public $usuario;
    public $index;

    public function usuarioNombre(User $usuario)
    {
        $this->emit('clienteNombre', $usuario);
    }
    
    public function render()
    {
        $fecha = Carbon::parse($this->usuario->fecha_de_ingreso)->format('d-m-Y');
        return view('livewire.usuario-modal', [
            'fecha' => $fecha,
        ]);
    }
}
