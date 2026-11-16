<?php

namespace App\Http\Controllers;

use App\Models\Entrega;
use App\Models\Solicitud;
use App\Models\EstadoSolicitud;
use App\Models\HistorialSolicitud;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EntregaController extends Controller
{
    public function store(Request $request, Solicitud $solicitud)
    {
        $datos = $request->validate([
            'observacion' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($solicitud->entrega()->exists()) {
            return back()->withErrors([
                'entrega' => 'Esta solicitud ya fue entregada.',
            ]);
        }

        if ($solicitud->estado->nombre !== 'Lista para recoger') {
            return back()->withErrors([
                'entrega' => 'La solicitud debe estar lista para recoger antes de registrar su entrega.',
            ]);
        }

        DB::transaction(function () use ($datos, $solicitud) {

            $estadoAnteriorId = $solicitud->estado_id;

            $estadoEntregada = EstadoSolicitud::where(
                'nombre',
                'Entregada'
            )->firstOrFail();

            Entrega::create([
                'solicitud_id' => $solicitud->id,
                'entregado_por' => Auth::id(),
                'fecha_entrega' => now(),
                'observacion' => $datos['observacion'] ?? null,
            ]);

            $solicitud->update([
                'estado_id' => $estadoEntregada->id,
            ]);

            HistorialSolicitud::create([
                'solicitud_id' => $solicitud->id,
                'estado_anterior_id' => $estadoAnteriorId,
                'estado_nuevo_id' => $estadoEntregada->id,
                'comentario' => 'Certificado entregado presencialmente al estudiante.',
                'usuario_id' => Auth::id(),
                'fecha_cambio' => now(),
            ]);

            Notificacion::create([
                'user_id' => $solicitud->estudiante->user_id,
                'solicitud_id' => $solicitud->id,
                'mensaje' => 'Tu certificado de estudiante regular ha sido entregado correctamente.',
                'leida' => false,
                'fecha_notificacion' => now(),
            ]);
        });

        return back()->with(
            'success',
            'Entrega registrada correctamente. La solicitud ahora figura como Entregada.'
        );
    }
}
