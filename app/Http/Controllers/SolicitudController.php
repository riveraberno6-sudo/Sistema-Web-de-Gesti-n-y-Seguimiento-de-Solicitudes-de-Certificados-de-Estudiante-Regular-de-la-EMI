<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Models\Estudiante;
use App\Models\EstadoSolicitud;
use App\Models\HistorialSolicitud;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SolicitudController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Mostrar solicitudes registradas
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $solicitudes = Solicitud::with([
            'estudiante',
            'estado'
        ])
            ->orderByDesc('fecha_solicitud')
            ->get();

        $estudiantes = Estudiante::with('user')
            ->orderBy('nombres')
            ->get();

        return view('admin.solicitudes.index', compact(
            'solicitudes',
            'estudiantes'
        ));
    }


    /*
    |--------------------------------------------------------------------------
    | Registrar una nueva solicitud
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $datos = $request->validate([
            'estudiante_id' => [
                'required',
                'exists:estudiantes,id'
            ],

            'fecha_solicitud' => [
                'required',
                'date'
            ],

            'observaciones' => [
                'nullable',
                'string',
                'max:1000'
            ],
        ]);

        DB::transaction(function () use ($datos) {

            // Obtener el estado inicial
            $estado = EstadoSolicitud::where(
                'nombre',
                'Recibida'
            )->firstOrFail();

            // Crear la solicitud
            $solicitud = Solicitud::create([
                'codigo_solicitud' => 'CER-' .
                    now()->format('Ymd') . '-' .
                    strtoupper(Str::random(6)),

                'estudiante_id' => $datos['estudiante_id'],

                'estado_id' => $estado->id,

                'fecha_solicitud' => $datos['fecha_solicitud'],

                'observaciones' => $datos['observaciones'] ?? null,

                'registrado_por' => Auth::id(),
            ]);

            // Registrar el historial inicial
            HistorialSolicitud::create([
                'solicitud_id' => $solicitud->id,

                'estado_anterior_id' => null,

                'estado_nuevo_id' => $estado->id,

                'comentario' => 'Solicitud registrada presencialmente.',

                'usuario_id' => Auth::id(),

                'fecha_cambio' => now(),
            ]);

            // Notificar al estudiante
            $estudiante = Estudiante::findOrFail(
                $datos['estudiante_id']
            );

            Notificacion::create([
                'user_id' => $estudiante->user_id,

                'solicitud_id' => $solicitud->id,

                'mensaje' => 'Tu solicitud ' .
                    $solicitud->codigo_solicitud .
                    ' fue registrada correctamente. Estado actual: Recibida.',

                'leida' => false,

                'fecha_notificacion' => now(),
            ]);
        });

        return redirect()
            ->route('admin.solicitudes.index')
            ->with(
                'success',
                'Solicitud registrada correctamente.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Mostrar detalles de una solicitud
    |--------------------------------------------------------------------------
    */

    public function show(Solicitud $solicitud)
    {
        $solicitud->load([
            'estudiante.user',
            'estado',
            'documentos',
            'historial.estadoAnterior',
            'historial.estadoNuevo',
            'historial.usuario',
            'entrega',
        ]);

        return view(
            'admin.solicitudes.show',
            compact('solicitud')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Cambiar el estado de una solicitud
    |--------------------------------------------------------------------------
    */

    public function cambiarEstado(
        Request $request,
        Solicitud $solicitud
    ) {
        $datos = $request->validate([
            'estado_id' => [
                'required',
                'exists:estados_solicitud,id'
            ],

            'comentario' => [
                'nullable',
                'string',
                'max:1000'
            ],

            'motivo_rechazo' => [
                'nullable',
                'string',
                'max:1000'
            ],
        ]);

        $estadoActual = $solicitud->estado;

        $nuevoEstado = EstadoSolicitud::findOrFail(
            $datos['estado_id']
        );

        // Definir las transiciones permitidas
        $transicionesPermitidas = [
            'Recibida' => [
                'En revisión'
            ],

            'En revisión' => [
                'Admitida',
                'Rechazada'
            ],

            'Admitida' => [
                'En preparación'
            ],

            'En preparación' => [
                'Lista para recoger'
            ],

            'Lista para recoger' => [
                'Entregada'
            ],

            'Rechazada' => [],

            'Entregada' => [],
        ];

        $estadosPermitidos =
            $transicionesPermitidas[$estadoActual->nombre] ?? [];

        if (!in_array(
            $nuevoEstado->nombre,
            $estadosPermitidos,
            true
        )) {
            return back()->withErrors([
                'estado_id' => 'No se permite cambiar de ' .
                    $estadoActual->nombre .
                    ' a ' .
                    $nuevoEstado->nombre .
                    '.',
            ])->withInput();
        }

        // Exigir motivo cuando la solicitud es rechazada
        if (
            $nuevoEstado->nombre === 'Rechazada' &&
            empty(trim($datos['motivo_rechazo'] ?? ''))
        ) {
            return back()->withErrors([
                'motivo_rechazo' =>
                    'Debes indicar el motivo del rechazo.',
            ])->withInput();
        }

        DB::transaction(function () use (
            $datos,
            $solicitud,
            $nuevoEstado
        ) {

            $estadoAnteriorId = $solicitud->estado_id;

            // Actualizar solicitud
            $solicitud->update([
                'estado_id' => $nuevoEstado->id,

                'motivo_rechazo' =>
                    $nuevoEstado->nombre === 'Rechazada'
                        ? $datos['motivo_rechazo']
                        : null,
            ]);

            // Registrar historial
            HistorialSolicitud::create([
                'solicitud_id' => $solicitud->id,

                'estado_anterior_id' => $estadoAnteriorId,

                'estado_nuevo_id' => $nuevoEstado->id,

                'comentario' => $datos['comentario'] ?? null,

                'usuario_id' => Auth::id(),

                'fecha_cambio' => now(),
            ]);

            // Preparar notificación
            $mensaje = 'Tu solicitud ' .
                $solicitud->codigo_solicitud .
                ' cambió al estado: ' .
                $nuevoEstado->nombre .
                '.';

            if ($nuevoEstado->nombre === 'Rechazada') {
                $mensaje .= ' Motivo: ' .
                    $datos['motivo_rechazo'];
            }

            // Notificar al estudiante
            Notificacion::create([
                'user_id' => $solicitud->estudiante->user_id,

                'solicitud_id' => $solicitud->id,

                'mensaje' => $mensaje,

                'leida' => false,

                'fecha_notificacion' => now(),
            ]);
        });

        return back()->with(
            'success',
            'Estado de la solicitud actualizado correctamente.'
        );
    }
}
