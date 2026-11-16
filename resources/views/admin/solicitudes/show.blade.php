
@extends('layouts.app')

@section('title', 'Detalle de solicitud | SIS-CER EMI')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold">Detalle de solicitud</h3>
            <p class="text-muted">Información y seguimiento del trámite.</p>
        </div>

        <a href="{{ route('admin.solicitudes.index') }}"
           class="btn btn-secondary">
            Volver a solicitudes
        </a>
    </div>

    <!-- INFORMACIÓN DE LA SOLICITUD -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Información de la solicitud</h5>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <strong>Código:</strong>
                    <p>{{ $solicitud->codigo_solicitud }}</p>
                </div>

                <div class="col-md-4 mb-3">
                    <strong>Fecha:</strong>
                    <p>{{ $solicitud->fecha_solicitud->format('d/m/Y') }}</p>
                </div>

                <div class="col-md-4 mb-3">
                    <strong>Estado actual:</strong>
                    <p>
                        <span class="badge bg-primary">
                            {{ $solicitud->estado->nombre }}
                        </span>
                    </p>
                </div>

                <div class="col-md-12 mb-3">
                    <strong>Observaciones:</strong>
                    <p>{{ $solicitud->observaciones ?? 'Sin observaciones' }}</p>
                </div>

                @if($solicitud->motivo_rechazo)
                    <div class="col-md-12">
                        <strong class="text-danger">Motivo de rechazo:</strong>
                        <p class="text-danger">{{ $solicitud->motivo_rechazo }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- DATOS DEL ESTUDIANTE -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header">
            <h5 class="mb-0">Datos del estudiante</h5>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <strong>Nombre completo:</strong>
                    <p>
                        {{ $solicitud->estudiante->nombres }}
                        {{ $solicitud->estudiante->apellido_paterno }}
                        {{ $solicitud->estudiante->apellido_materno }}
                    </p>
                </div>

                <div class="col-md-3 mb-3">
                    <strong>Registro universitario:</strong>
                    <p>{{ $solicitud->estudiante->ru }}</p>
                </div>

                <div class="col-md-3 mb-3">
                    <strong>Cédula de identidad:</strong>
                    <p>{{ $solicitud->estudiante->ci }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Correo electrónico:</strong>
                    <p>{{ $solicitud->estudiante->user->email }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Carrera:</strong>
                    <p>{{ $solicitud->estudiante->carrera }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- DOCUMENTOS -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header">
            <h5 class="mb-0">Documentos recibidos presencialmente</h5>
        </div>

        <div class="card-body">

            <form action="{{ route('admin.documentos.store', $solicitud->id) }}"
                  method="POST" class="mb-4">
                @csrf

                <div class="row">
                    <div class="col-md-5 mb-3">
                        <label class="form-label">Nombre del documento</label>
                        <input type="text" name="nombre_documento"
                               class="form-control"
                               placeholder="Ej. Fotocopia de carnet" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">¿Documento recibido?</label>
                        <select name="recibido" class="form-select" required>
                            <option value="1">Sí, recibido</option>
                            <option value="0">No recibido</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Observación</label>
                        <input type="text" name="observacion"
                               class="form-control"
                               placeholder="Observación opcional">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    Registrar documento
                </button>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Documento</th>
                            <th>Estado</th>
                            <th>Fecha de recepción</th>
                            <th>Observación</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($solicitud->documentos as $documento)
                            <tr>
                                <td>{{ $documento->nombre_documento }}</td>
                                <td>
                                    @if($documento->recibido)
                                        <span class="badge bg-success">Recibido</span>
                                    @else
                                        <span class="badge bg-danger">Pendiente</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $documento->fecha_recepcion
                                        ? $documento->fecha_recepcion->format('d/m/Y H:i')
                                        : 'Sin recepción' }}
                                </td>
                                <td>{{ $documento->observacion ?? 'Sin observaciones' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    Todavía no se registraron documentos.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ACTUALIZAR ESTADO -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header">
            <h5 class="mb-0">Actualizar estado de la solicitud</h5>
        </div>

        <div class="card-body">
            @php
                $transiciones = [
                    'Recibida' => ['En revisión'],
                    'En revisión' => ['Admitida', 'Rechazada'],
                    'Admitida' => ['En preparación'],
                    'En preparación' => ['Lista para recoger'],
                ];

                $permitidos = $transiciones[$solicitud->estado->nombre] ?? [];
                $estados = \App\Models\EstadoSolicitud::whereIn('nombre', $permitidos)->get();
            @endphp

            @if($estados->isNotEmpty())
                <form action="{{ route('admin.solicitudes.estado', $solicitud->id) }}"
                      method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nuevo estado</label>
                        <select name="estado_id" class="form-select" required>
                            <option value="">Seleccione el nuevo estado</option>
                            @foreach($estados as $estado)
                                <option value="{{ $estado->id }}">
                                    {{ $estado->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Comentario</label>
                        <textarea name="comentario" class="form-control"
                                  rows="2"
                                  placeholder="Comentario sobre el cambio de estado"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Motivo de rechazo (si corresponde)</label>
                        <textarea name="motivo_rechazo" class="form-control"
                                  rows="2"
                                  placeholder="Indique el motivo si la solicitud será rechazada"></textarea>
                    </div>

                    <button type="submit" class="btn btn-success">
                        Actualizar estado
                    </button>
                </form>
            @else
                <div class="alert alert-info mb-0">
                    La solicitud se encuentra en un estado final o no tiene
                    transiciones disponibles.
                </div>
            @endif
        </div>
    </div>

    <!-- HISTORIAL -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header">
            <h5 class="mb-0">Historial de seguimiento</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Fecha</th>
                            <th>Estado anterior</th>
                            <th>Estado nuevo</th>
                            <th>Responsable</th>
                            <th>Comentario</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($solicitud->historial as $historial)
                            <tr>
                                <td>{{ $historial->fecha_cambio->format('d/m/Y H:i') }}</td>
                                <td>{{ $historial->estadoAnterior->nombre ?? 'Inicial' }}</td>
                                <td>{{ $historial->estadoNuevo->nombre }}</td>
                                <td>{{ $historial->usuario->name ?? 'No disponible' }}</td>
                                <td>{{ $historial->comentario ?? 'Sin comentario' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">
                                    No existe historial registrado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ENTREGA -->
    @if($solicitud->estado->nombre === 'Lista para recoger')
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Registrar entrega del certificado</h5>
            </div>

            <div class="card-body">
                <p>El certificado está listo para ser recogido por el estudiante.</p>

                <form action="{{ route('admin.entregas.store', $solicitud->id) }}"
                      method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Observación de entrega</label>
                        <textarea name="observacion" class="form-control"
                                  rows="2"
                                  placeholder="Observación opcional"></textarea>
                    </div>

                    <button type="submit" class="btn btn-success">
                        Confirmar entrega del certificado
                    </button>
                </form>
            </div>
        </div>
    @endif

    @if($solicitud->entrega)
        <div class="alert alert-success">
            <h5>Certificado entregado</h5>
            <p class="mb-1">
                <strong>Fecha:</strong>
                {{ $solicitud->entrega->fecha_entrega->format('d/m/Y H:i') }}
            </p>
            <p class="mb-0">
                <strong>Observación:</strong>
                {{ $solicitud->entrega->observacion ?? 'Sin observaciones' }}
            </p>
        </div>
    @endif

</div>

@endsection
