<?php

namespace App\Http\Livewire;

use App\Models\Propuesta;
use Livewire\Component;

class PropuestaParaCuotas extends Component
{
    public $operacion;
    public $deudorId;
    public $monto_negociado;
    public $honorarios;
    public $monto_honorarios;
    public $monto_total;
    public $anticipo;
    public $monto_a_pagar_en_cuotas;
    public $cantidad_de_cuotas_uno;
    public $monto_de_cuotas_uno;
    public $resultados = false;
    public $confirmarPropuesta = false;
    public $formulario = true;
    public $accion;
    public $estado;
    public $vencimiento;
    public $observaciones;
    public $operacion_id;

    public function mount()
    {
        $this->monto_honorarios = ($this->monto_negociado * $this->honorarios) / 100;
        $this->monto_total = $this->monto_negociado + $this->monto_honorarios;
    }

    protected $rules = [
        'anticipo'=> 'required|numeric',
        'cantidad_de_cuotas_uno'=> 'required|numeric',
    ];

    public function calcularCuotasConAnticipo()
    {
        $datos = $this->validate();
        $this->formulario = false;
        $this->anticipo = $datos['anticipo'];
        $this->monto_a_pagar_en_cuotas = $this->monto_total - $datos['anticipo'];
        $this->monto_de_cuotas_uno = $this->monto_a_pagar_en_cuotas / $datos['cantidad_de_cuotas_uno'];
        $this->resultados = true;
    }

    public function confirmarPropuesta()
    {
        $this->confirmarPropuesta = true;
    }

    public function cancelarPropuesta()
    {
        $this->confirmarPropuesta = false;
        $this->resultados = false;
        $this->formulario = true;
        $this->anticipo = null;
        $this->cantidad_de_cuotas_uno = null;
    }
    
    public function guardarPropuesta()
    {
        $reglasSegundoFormulario = [
            'accion' => 'required',
            'estado' => 'required',
            'vencimiento' => 'required|date',
            'observaciones' => 'required|max:255'
        ];

        $this->validate($reglasSegundoFormulario);

        $propuesta = new Propuesta();
        $propuesta->usuario_ultima_modificacion_id = auth()->id();      
        $propuesta->operacion_id = $this->operacion->id;
        $propuesta->deudor_id = $this->deudorId;
        $propuesta->monto_negociado = $this->monto_negociado;
        $propuesta->honorarios = $this->monto_honorarios;
        $propuesta->monto_total = $this->monto_total;
        $propuesta->anticipo = $this->anticipo;
        $propuesta->monto_a_pagar_en_cuotas = $this->monto_a_pagar_en_cuotas;
        $propuesta->cantidad_de_cuotas_uno = $this->cantidad_de_cuotas_uno;
        $propuesta->monto_de_cuotas_uno = $this->monto_de_cuotas_uno;
        $propuesta->accion = $this->accion;
        $propuesta->estado = $this->estado;
        $propuesta->vencimiento = $this->vencimiento;
        $propuesta->observaciones = $this->observaciones;
        $propuesta->tipo_de_propuesta = 3;

        $propuesta->save();

        return redirect()->route('historial.propuesta', ['operacion' => $this->operacion->id])->with('message', 'Gestión generada correctamente');
    }

    public function render()
    {
        return view('livewire.propuesta-para-cuotas', [
            'monto_honorarios' => $this->monto_honorarios,
            'monto_total' => $this->monto_total,
            'anticipo' => $this->anticipo,
            'monto_a_pagar_en_cuotas' => $this->monto_a_pagar_en_cuotas,
            'monto_de_cuotas_uno' => $this->monto_de_cuotas_uno,
        ]);
    }
}


