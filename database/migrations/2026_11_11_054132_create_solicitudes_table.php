
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->id();

            $table->string('codigo_solicitud')->unique();

            $table->foreignId('estudiante_id')
                ->constrained('estudiantes');

            $table->foreignId('estado_id')
                ->constrained('estados_solicitud');

            $table->date('fecha_solicitud');

            $table->text('observaciones')->nullable();
            $table->text('motivo_rechazo')->nullable();

            $table->foreignId('registrado_por')
                ->constrained('users');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitudes');
    }
};
