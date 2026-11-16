
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historial_solicitud', function (Blueprint $table) {
            $table->id();

            $table->foreignId('solicitud_id')
                ->constrained('solicitudes')
                ->cascadeOnDelete();

            $table->foreignId('estado_anterior_id')
                ->nullable()
                ->constrained('estados_solicitud');

            $table->foreignId('estado_nuevo_id')
                ->constrained('estados_solicitud');

            $table->text('comentario')->nullable();

            $table->foreignId('usuario_id')
                ->constrained('users');

            $table->dateTime('fecha_cambio');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historial_solicitud');
    }
};
