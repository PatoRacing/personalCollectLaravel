<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'clientes';
    
    protected $fillable = [
        'nombre',
        'localidad',
        'codigo_postal',
        'provincia',
        'contacto',
        'telefono',
        'email',
        'estado',
        'creado',
        'usuario_ultima_modificacion_id',
        'fecha_ultima_modificacion'
    ];

    public function usuarioUltimaModificacion()
    {
        return $this->belongsTo(User::class);
    }
    public function operaciones()
    {
        return $this->hasMany(Operacion::class);
    }
}
