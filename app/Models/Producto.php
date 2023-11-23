<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'honorarios',
        'comision_cliente',
        'usuario_ultima_modificacion_id',
        'cliente_id',
        'estado'
    ];

    public function usuarioUltimaModificacion()
    {
        return $this->belongsTo(User::class);
    }

    public function clienteId()
    {
        return $this->belongsTo(Cliente::class);
    }
}
