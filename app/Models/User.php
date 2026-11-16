<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Un usuario estudiante tiene un registro académico
    public function estudiante(): HasOne
    {
        return $this->hasOne(Estudiante::class);
    }

    // Solicitudes registradas por el administrador
    public function solicitudesRegistradas(): HasMany
    {
        return $this->hasMany(Solicitud::class, 'registrado_por');
    }

    // Notificaciones recibidas por el usuario
    public function notificaciones(): HasMany
    {
        return $this->hasMany(Notificacion::class);
    }
}
