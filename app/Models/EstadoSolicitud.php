<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EstadoSolicitud extends Model
{
    protected $table = 'estados_solicitud';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function solicitudes(): HasMany
    {
        return $this->hasMany(Solicitud::class, 'estado_id');
    }

    public function historialAnterior(): HasMany
    {
        return $this->hasMany(
            HistorialSolicitud::class,
            'estado_anterior_id'
        );
    }

    public function historialNuevo(): HasMany
    {
        return $this->hasMany(
            HistorialSolicitud::class,
            'estado_nuevo_id'
        );
    }
}
