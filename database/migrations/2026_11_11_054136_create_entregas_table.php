<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entregas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('solicitud_id')
                ->unique()
                ->constrained('solicitudes')
                ->cascadeOnDelete();

            $table->foreignId('entregado_por')
                ->constrained('users');

            $table->dateTime('fecha_entrega');
            $table->text('observacion')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entregas');
    }
};
