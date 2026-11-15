<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'correo',
        'password',
        'rol_id'
    ];

    protected $hidden = ['password',];

    protected function casts(): array
    {
        return ['password' => 'hashed',];
    }

    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    public function codigosVerificacion()
    {
        return $this->hasMany(CodigoVerificacion::class, 'usuario_id');
    }
}