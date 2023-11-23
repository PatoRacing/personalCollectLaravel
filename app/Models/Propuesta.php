<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Propuesta extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_ultima_modificacion_id',
        'operacion_id',
        'monto_negociado',
        'honorarios',
        'monto_total',
        'porcentaje_quita',
        'monto_de_quita',
        'total_a_pagar',
        'anticipo',
        'cantidad_de_cuotas_uno',
        'monto_de_cuotas_uno',
        'cantidad_de_cuotas_dos',
        'monto_de_cuotas_dos',
        'vencimiento',
        'accion',
        'estado',
        'observaciones',
        'monto_a_pagar_en_cuotas',
        'tipo_de_propuesta',
        'deudor_id'
    ];

    public function usuarioUltimaModificacion()
    {
        return $this->belongsTo(User::class, 'usuario_ultima_modificacion_id');
    }

    public function operacionId()
    {
        return $this->belongsTo(Operacion::class, 'operacion_id');
    }

    public function deudorId()
    {
        return $this->belongsTo(Deudor::class, 'deudor_id');
    }
}
