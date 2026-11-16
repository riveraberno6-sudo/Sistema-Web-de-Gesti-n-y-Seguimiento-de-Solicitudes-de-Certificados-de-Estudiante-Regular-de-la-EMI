<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Solicitud extends Model
{
    protected $table = 'solicitudes';

    protected $fillable = [
        'codigo_solicitud',
        'estudiante_id',
        'estado_id',
        'fecha_solicitud',
        'observaciones',
        'motivo_rechazo',
        'registrado_por',
    ];

    protected function casts(): array
    {
        return [
            'fecha_solicitud' => 'date',
        ];
    }

    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(Estudiante::class);
    }

    public function estado(): BelongsTo
    {
        return $this->belongsTo(EstadoSolicitud::class, 'estado_id');
    }

    public function documentos(): HasMany
    {
        return $this->hasMany(Documento::class, 'solicitud_id');
    }

    public function historial(): HasMany
    {
        return $this->hasMany(HistorialSolicitud::class, 'solicitud_id');
    }

    public function entrega(): HasOne
    {
        return $this->hasOne(Entrega::class, 'solicitud_id');
    }

    public function administrador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }
}
