<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'apellido',
        'estado_de_usuario',
        'dni',
        'rol_id',
        'estado_de_usuario_id',
        'telefono',
        'domicilio',
        'localidad',
        'codigo_postal',
        'fecha_de_ingreso',
        'email',
        'password',
        'usuario_ultima_modificacion',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function rols()
    {
        return $this->belongsTo(Rol::class);
    }

    public function estado_de_usuarios()
    {
        return $this->belongsTo(EstadoDeUsuario::class);
    }
}
