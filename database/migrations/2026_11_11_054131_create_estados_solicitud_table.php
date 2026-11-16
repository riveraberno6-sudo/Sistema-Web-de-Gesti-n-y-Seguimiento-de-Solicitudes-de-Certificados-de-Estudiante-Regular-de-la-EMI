
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estados_solicitud', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->unique();
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });

        DB::table('estados_solicitud')->insert([
            [
                'nombre' => 'Recibida',
                'descripcion' => 'La solicitud fue registrada y se recibieron los documentos.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'En revisión',
                'descripcion' => 'La documentación está siendo revisada.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Admitida',
                'descripcion' => 'La solicitud fue aprobada.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Rechazada',
                'descripcion' => 'La solicitud fue rechazada y tiene un motivo registrado.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'En preparación',
                'descripcion' => 'El certificado está siendo preparado.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Lista para recoger',
                'descripcion' => 'El certificado está disponible para su recogida presencial.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Entregada',
                'descripcion' => 'El certificado fue entregado al estudiante.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('estados_solicitud');
    }
};
