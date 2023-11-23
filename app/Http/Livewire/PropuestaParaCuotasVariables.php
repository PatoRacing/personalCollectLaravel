<?php

namespace App\Http\Livewire;

use App\Models\Propuesta;
use Illuminate\Validation\Rule;
use Livewire\Component;

class PropuestaParaCuotasVariables extends Component
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
    public $porcentaje_grupo_uno;
    public $monto_grupo_uno;
    public $monto_de_cuotas_uno;
    public $cantidad_de_cuotas_dos;
    public $porcentaje_grupo_dos;
    public $monto_grupo_dos;
    public $monto_de_cuotas_dos;
    public $resultados = false;
    public $confirmarPropuesta = false;
    public $accion;
    public $estado;
    public $vencimiento;
    public $observaciones;
    public $operacion_id;
    public $formulario = true;
    

    public function mount()
    {
        $this->monto_honorarios = ($this->monto_negociado * $this->honorarios) / 100;
        $this->monto_total = $this->monto_negociado + $this->monto_honorarios;
    }

    protected $rules = [
        'anticipo'=> 'required|numeric',
        'cantidad_de_cuotas_uno'=> 'required|numeric',
        'porcentaje_grupo_uno'=> 'required|numeric|numeric',
        'cantidad_de_cuotas_dos'=> 'required|numeric',
        'porcentaje_grupo_dos' => 'required|numeric',
    ];
  
    public function calcularCuotasVariablesConAnticipo()
    {
        $datos = $this->validate();
        $sumaPorcentajes = $datos['porcentaje_grupo_uno'] + $datos['porcentaje_grupo_dos'];

        if ($sumaPorcentajes !== 100) {
            // Agrega un mensaje de error
            $this->addError('porcentaje_grupo_uno', 'La suma de porcentajes debe ser igual a 100');
            $this->addError('porcentaje_grupo_dos', 'La suma de porcentajes debe ser igual a 100');
            return;
        }

        $this->formulario = false;

        $this->anticipo = $datos['anticipo'];
        $this->monto_a_pagar_en_cuotas = $this->monto_total - $datos['anticipo'];
        $this->monto_grupo_uno = ($this->monto_a_pagar_en_cuotas * $datos['porcentaje_grupo_uno'])/100;
        $this->monto_de_cuotas_uno = $this->monto_grupo_uno / $datos['cantidad_de_cuotas_uno'];

        $this->monto_grupo_dos = ($this->monto_a_pagar_en_cuotas * $datos['porcentaje_grupo_dos'])/100;
        $this->monto_de_cuotas_dos = $this->monto_grupo_dos / $datos['cantidad_de_cuotas_dos'];

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
        $this->porcentaje_grupo_uno = null;
        $this->cantidad_de_cuotas_dos = null;
        $this->porcentaje_grupo_dos = null;
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
        $propuesta->cantidad_de_cuotas_dos = $this->cantidad_de_cuotas_dos;
        $propuesta->monto_de_cuotas_dos = $this->monto_de_cuotas_dos;
        $propuesta->accion = $this->accion;
        $propuesta->estado = $this->estado;
        $propuesta->vencimiento = $this->vencimiento;
        $propuesta->observaciones = $this->observaciones;
        $propuesta->tipo_de_propuesta = 4;

        $propuesta->save();

        return redirect()->route('historial.propuesta', ['operacion' => $this->operacion->id])->with('message', 'Gestión generada correctamente');
    }
    
    public function render()
    {
        return view('livewire.propuesta-para-cuotas-variables', [
            'monto_honorarios' => $this->monto_honorarios,
            'monto_total' => $this->monto_total,
            'anticipo' => $this->anticipo,
            'monto_a_pagar_en_cuotas' => $this->monto_a_pagar_en_cuotas,
            'monto_grupo_uno' => $this->monto_grupo_uno,
            'cantidad_de_cuotas_uno' => $this->cantidad_de_cuotas_uno,
            'monto_de_cuotas_uno' => $this->monto_de_cuotas_uno,
            'monto_grupo_dos' => $this->monto_grupo_dos,
            'cantidad_de_cuotas_dos' => $this->cantidad_de_cuotas_dos,
            'monto_de_cuotas_dos' => $this->monto_de_cuotas_dos,
        ]);
    }
}
