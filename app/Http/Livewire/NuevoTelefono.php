<?php

namespace App\Http\Livewire;

use App\Models\Telefono;
use Livewire\Component;

class NuevoTelefono extends Component
{
    public $deudorId;
    public $deudor;
    public $tipo;
    public $contacto;
    public $numero;
    public $estado;

    protected $rules = [
        'tipo'=> 'required|max:255',
        'contacto'=> 'required|max:255',
        'numero'=> 'required|max:255',
        'estado'=> 'required|max:255'
    ];

    public function nuevoTelefono()
    {
        $datos = $this->validate();
        $deudorId = $this->deudor['id'];

        $telefono = Telefono::create([
            'deudor_id'=> $deudorId,
            'tipo'=>$datos['tipo'],
            'contacto'=>$datos['contacto'],
            'numero'=>$datos['numero'],
            'estado'=>$datos['estado'],
            'usuario_ultima_modificacion_id'=>auth()->id(),
        ]);

        return redirect()->route('deudor.perfil', ['deudor' => $deudorId])
                ->with('message', 'Teléfono agregado correctamente');
    }

    public function render()
    {
        return view('livewire.nuevo-telefono');
    }
}
