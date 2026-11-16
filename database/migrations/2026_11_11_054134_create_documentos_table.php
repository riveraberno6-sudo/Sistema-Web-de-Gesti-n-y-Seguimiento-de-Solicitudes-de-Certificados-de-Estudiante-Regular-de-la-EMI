
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('solicitud_id')
                ->constrained('solicitudes')
                ->cascadeOnDelete();

            $table->string('nombre_documento');
            $table->boolean('recibido')->default(false);
            $table->text('observacion')->nullable();
            $table->dateTime('fecha_recepcion')->nullable();

            $table->foreignId('registrado_por')
                ->constrained('users');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentos');
    }
};
