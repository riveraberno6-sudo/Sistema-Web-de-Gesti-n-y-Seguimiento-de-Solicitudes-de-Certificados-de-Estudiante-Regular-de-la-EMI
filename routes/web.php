<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\EntregaController;

/*
|--------------------------------------------------------------------------
| Rutas públicas
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'mostrarLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.post');


/*
|--------------------------------------------------------------------------
| Rutas protegidas: usuarios autenticados
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::get('/dashboard', function (Request $request) {
        if ($request->user()->role === 'administrador') {
            return view('admin.dashboard');
        }

        return redirect()->route('estudiante.notificaciones.index');
    })->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | Rutas del administrador
    |--------------------------------------------------------------------------
    */

    Route::middleware('can:administrador')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            // Gestión de estudiantes
            Route::get('/estudiantes', [EstudianteController::class, 'index'])
                ->name('estudiantes.index');

            Route::post('/estudiantes', [EstudianteController::class, 'store'])
                ->name('estudiantes.store');

            Route::get('/estudiantes/{estudiante}', [EstudianteController::class, 'show'])
                ->name('estudiantes.show');

            Route::put('/estudiantes/{estudiante}', [EstudianteController::class, 'update'])
                ->name('estudiantes.update');


            // Gestión de solicitudes
            Route::get('/solicitudes', [SolicitudController::class, 'index'])
                ->name('solicitudes.index');

            Route::post('/solicitudes', [SolicitudController::class, 'store'])
                ->name('solicitudes.store');

            Route::get('/solicitudes/{solicitud}', [SolicitudController::class, 'show'])
                ->name('solicitudes.show');

            Route::put('/solicitudes/{solicitud}/estado', [SolicitudController::class, 'cambiarEstado'])
                ->name('solicitudes.estado');


            // Registro de documentos recibidos presencialmente
            Route::post('/solicitudes/{solicitud}/documentos', [DocumentoController::class, 'store'])
                ->name('documentos.store');

            Route::put('/documentos/{documento}', [DocumentoController::class, 'update'])
                ->name('documentos.update');


            // Registro de entrega del certificado
            Route::post('/solicitudes/{solicitud}/entrega', [EntregaController::class, 'store'])
                ->name('entregas.store');
        });


    /*
    |--------------------------------------------------------------------------
    | Rutas del estudiante
    |--------------------------------------------------------------------------
    */

    Route::middleware('can:estudiante')
        ->prefix('estudiante')
        ->name('estudiante.')
        ->group(function () {

            // Notificaciones
            Route::get('/notificaciones', [NotificacionController::class, 'index'])
                ->name('notificaciones.index');

            Route::put('/notificaciones/{notificacion}/leida', [NotificacionController::class, 'marcarLeida'])
                ->name('notificaciones.leida');

            Route::put('/notificaciones-leidas', [NotificacionController::class, 'marcarTodasLeidas'])
                ->name('notificaciones.todas-leidas');
        });

});
