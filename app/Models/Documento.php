<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Documento extends Model
{
    protected $table = 'documentos';

    protected $fillable = [
        'solicitud_id',
        'nombre_documento',
        'recibido',
        'observacion',
        'fecha_recepcion',
        'registrado_por',
    ];

    protected function casts(): array
    {
        return [
            'recibido' => 'boolean',
            'fecha_recepcion' => 'datetime',
        ];
    }

    public function solicitud(): BelongsTo
    {
        return $this->belongsTo(Solicitud::class, 'solicitud_id');
    }

    public function administrador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }
}
