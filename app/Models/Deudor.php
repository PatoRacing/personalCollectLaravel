<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deudor extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'tipo_doc',
        'nro_doc',
        'domicilio',
        'localidad',
        'codigo_postal',
        'telefono',
        'usuario_ultima_modificacion_id',
    ];

    public function usuarioUltimaModificacion()
    {
        return $this->belongsTo(User::class);
    }


}
