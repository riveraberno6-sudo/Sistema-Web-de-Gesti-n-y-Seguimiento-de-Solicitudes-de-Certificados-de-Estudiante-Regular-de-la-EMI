<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistorialSolicitud extends Model
{
    protected $table = 'historial_solicitud';

    protected $fillable = [
        'solicitud_id',
        'estado_anterior_id',
        'estado_nuevo_id',
        'comentario',
        'usuario_id',
        'fecha_cambio',
    ];

    protected function casts(): array
    {
        return [
            'fecha_cambio' => 'datetime',
        ];
    }

    public function solicitud(): BelongsTo
    {
        return $this->belongsTo(Solicitud::class, 'solicitud_id');
    }

    public function estadoAnterior(): BelongsTo
    {
        return $this->belongsTo(
            EstadoSolicitud::class,
            'estado_anterior_id'
        );
    }

    public function estadoNuevo(): BelongsTo
    {
        return $this->belongsTo(
            EstadoSolicitud::class,
            'estado_nuevo_id'
        );
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
