<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operacion extends Model
{
    use HasFactory;
    protected $fillable = [
        'segmento',
        'producto_id',
        'operacion',
        'fecha_apertura',
        'cant_cuotas',
        'sucursal',
        'fecha_atraso',
        'dias_atraso',
        'deuda_total',
        'deuda_capital',
        'fecha_ult_pago',
        'estado',
        'fecha_asignacion',
        'ciclo',
        'situacion',
        'cliente_id',
        'deudor_id',
        'usuario_ultima_modificacion_id',
        'nro_doc',
        'usuario_asignado_id',
    ];

    public function productoId()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    public function clienteId()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function deudorId()
    {
        return $this->belongsTo(Deudor::class, 'deudor_id');
    }

    public function usuarioUltimaModificacion()
    {
        return $this->belongsTo(User::class);
    }
    public function usuarioAsignado()
    {
        return $this->belongsTo(User::class);
    }

    public function propuestas()
    {
        return $this->hasMany(Propuesta::class, 'operacion_id');
    }

}
