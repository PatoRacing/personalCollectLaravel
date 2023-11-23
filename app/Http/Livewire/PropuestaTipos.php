<?php

namespace App\Http\Livewire;

use Livewire\Component;

class PropuestaTipos extends Component
{
    public $operacion;
    public $monto_negociado;
    public $componentes;
    public $honorarios;
    public $deudorId;

    protected $rules = [
        'monto_negociado'=> 'required|numeric'
    ];

    public function pasoUno()
    {
        $datos = $this->validate();
        $this->componentes = true;
        $monto_negociado=$datos['monto_negociado'];
    }

    public function render()
    {
        return view('livewire.propuesta-tipos');
    }
}
