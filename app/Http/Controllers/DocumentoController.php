<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentoController extends Controller
{
    public function store(Request $request, Solicitud $solicitud)
    {
        $datos = $request->validate([
            'nombre_documento' => ['required', 'string', 'max:150'],
            'recibido' => ['required', 'boolean'],
            'observacion' => ['nullable', 'string', 'max:1000'],
        ]);

        Documento::create([
            'solicitud_id' => $solicitud->id,
            'nombre_documento' => $datos['nombre_documento'],
            'recibido' => $datos['recibido'],
            'observacion' => $datos['observacion'] ?? null,
            'fecha_recepcion' => $datos['recibido'] ? now() : null,
            'registrado_por' => Auth::id(),
        ]);

        return back()->with(
            'success',
            'Documento registrado correctamente.'
        );
    }

    public function update(Request $request, Documento $documento)
    {
        $datos = $request->validate([
            'nombre_documento' => ['required', 'string', 'max:150'],
            'recibido' => ['required', 'boolean'],
            'observacion' => ['nullable', 'string', 'max:1000'],
        ]);

        $documento->update([
            'nombre_documento' => $datos['nombre_documento'],
            'recibido' => $datos['recibido'],
            'observacion' => $datos['observacion'] ?? null,
            'fecha_recepcion' => $datos['recibido']
                ? ($documento->fecha_recepcion ?? now())
                : null,
        ]);

        return back()->with(
            'success',
            'Documento actualizado correctamente.'
        );
    }
}
