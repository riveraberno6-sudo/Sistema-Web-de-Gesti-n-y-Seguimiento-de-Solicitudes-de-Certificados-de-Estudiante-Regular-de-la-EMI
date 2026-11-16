<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Estudiante extends Model
{
    protected $table = 'estudiantes';

    protected $fillable = [
        'user_id',
        'ru',
        'ci',
        'nombres',
        'apellido_paterno',
        'apellido_materno',
        'carrera',
        'semestre',
        'telefono',
    ];

    // Relación con la cuenta de usuario
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Un estudiante puede tener varias solicitudes
    public function solicitudes(): HasMany
    {
        return $this->hasMany(Solicitud::class);
    }
}
