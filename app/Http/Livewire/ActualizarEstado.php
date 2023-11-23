<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class ActualizarEstado extends Component
{
    public $usuario;
    public $usuarioAutenticado;

    public function actualizarEstado(User $usuario)
    {
        if($usuario->estado_de_usuario_id === 1)
        {
            $usuario->estado_de_usuario_id = 2;
            $usuario->usuario_ultima_modificacion =  auth()->id();

        } else {
            $usuario->estado_de_usuario_id = 1;
            $usuario->usuario_ultima_modificacion =  auth()->id();
        }
        
        $usuario->save();
        $this->emit('actualizacionCompleta');
    }

    //$this->emit('refreshComponent');
    public function render()
    {
        return view('livewire.actualizar-estado');
    }
}
