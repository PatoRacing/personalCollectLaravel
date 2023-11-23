<?php

namespace App\Http\Livewire;

use App\Models\Telefono;
use Livewire\Component;

class ActualizarTelefono extends Component
{
    public $telefono;
    public $telefono_id;
    public $tipo;
    public $contacto;
    public $numero;
    public $estado;

    protected $rules = [
        'tipo'=> 'required|max:255',
        'contacto'=> 'required|max:255',
        'numero'=> 'required|max:255',
        'estado'=> 'required|max:255',
    ];

    public function mount(Telefono $telefono)
    {
        $this->telefono_id = $telefono->id;
        $this->tipo = $telefono->tipo;
        $this->contacto = $telefono->contacto;
        $this->numero = $telefono->numero;
        $this->estado = $telefono->estado;
    }

    public function actualizarTelefono()
    {
        $datos = $this->validate();
        $telefono = Telefono::find($this->telefono_id);

        $telefono->tipo = $datos['tipo'];
        $telefono->contacto = $datos['contacto'];
        $telefono->numero = $datos['numero'];
        $telefono->estado = $datos['estado'];
        $telefono->usuario_ultima_modificacion_id = auth()->id();

        $telefono->save();

        return redirect()->route('deudor.perfil', ['deudor' => $telefono->deudor_id])->with('message', 'Teléfono actualizado correctamente');
    }

    public function render()
    {
        return view('livewire.actualizar-telefono');
    }
}
