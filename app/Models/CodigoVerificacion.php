<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CodigoVerificacion extends Model
{
    protected $table = 'codigos_verificacion';

    protected $fillable = [
        'usuario_id',
        'codigo',
        'expira_en',
        'utilizado'
    ];

    protected function casts(): array
    {
        return [
            'expira_en' => 'datetime',
            'utilizado' => 'boolean'
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}