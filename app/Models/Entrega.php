<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Entrega extends Model
{
    protected $table = 'entregas';

    protected $fillable = [
        'solicitud_id',
        'entregado_por',
        'fecha_entrega',
        'observacion',
    ];

    protected function casts(): array
    {
        return [
            'fecha_entrega' => 'datetime',
        ];
    }

    public function solicitud(): BelongsTo
    {
        return $this->belongsTo(Solicitud::class, 'solicitud_id');
    }

    public function administrador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'entregado_por');
    }
}
