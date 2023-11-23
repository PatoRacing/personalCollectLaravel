<?php

namespace App\Http\Livewire;

use App\Models\Propuesta;
use Livewire\Component;

class PropuestaParaCancelacionConDescuento extends Component
{
    public $operacion;
    public $deudorId;
    public $monto_negociado;
    public $honorarios;
    public $monto_honorarios;
    public $monto_total;
    public $porcentaje_quita;
    public $monto_quita;
    public $cantidad_de_cuotas_uno;
    public $resultados = false;
    public $formulario = true;
    public $monto_a_pagar_en_cuotas;
    public $monto_de_cuotas_uno;
    public $confirmarPropuesta  = false;
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
        'porcentaje_quita'=> 'required|numeric',
        'cantidad_de_cuotas_uno'=> 'required|numeric',
    ];

    public function calcularQuitaConDescuento()
    {
        $datos = $this->validate();
        $this->formulario = false;
        $this->monto_quita = ($this->monto_total * $datos['porcentaje_quita']) / 100;
        $this->monto_a_pagar_en_cuotas = $this->monto_total - $this->monto_quita;
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
        $this->porcentaje_quita = null;
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
        $propuesta->porcentaje_quita = $this->porcentaje_quita;
        $propuesta->monto_de_quita = $this->monto_quita;
        $propuesta->monto_a_pagar_en_cuotas = $this->monto_a_pagar_en_cuotas;
        $propuesta->cantidad_de_cuotas_uno = $this->cantidad_de_cuotas_uno;
        $propuesta->monto_de_cuotas_uno = $this->monto_de_cuotas_uno;
        $propuesta->accion = $this->accion;
        $propuesta->estado = $this->estado;
        $propuesta->vencimiento = $this->vencimiento;
        $propuesta->observaciones = $this->observaciones;
        $propuesta->tipo_de_propuesta = 2;

        $propuesta->save();

        return redirect()->route('historial.propuesta', ['operacion' => $this->operacion->id])->with('message', 'Gestión generada correctamente');
    }
    
    public function render()
    {
        return view('livewire.propuesta-para-cancelacion-con-descuento',[
            'monto_honorarios' => $this->monto_honorarios,
            'monto_total' => $this->monto_total,
            'monto_quita' => $this->monto_quita,
            'monto_a_pagar_en_cuotas' => $this->monto_a_pagar_en_cuotas,
            'monto_de_cuotas_uno' => $this->monto_de_cuotas_uno,
        ]);
    }
}
