<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Support\Facades\Auth;

class NotificacionController extends Controller
{
    public function index()
    {
        $notificaciones = Notificacion::where('user_id', Auth::id())
            ->with('solicitud')
            ->orderByDesc('fecha_notificacion')
            ->get();

        return view('estudiante.notificaciones.index', compact('notificaciones'));
    }

    public function marcarLeida(Notificacion $notificacion)
    {
        if ($notificacion->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para acceder a esta notificación.');
        }

        $notificacion->update([
            'leida' => true,
        ]);

        return back()->with(
            'success',
            'Notificación marcada como leída.'
        );
    }

    public function marcarTodasLeidas()
    {
        Notificacion::where('user_id', Auth::id())
            ->where('leida', false)
            ->update([
                'leida' => true,
            ]);

        return back()->with(
            'success',
            'Todas las notificaciones fueron marcadas como leídas.'
        );
    }
}
