
@extends('layouts.app')

@section('title', 'Mis notificaciones | SIS-CER EMI')

@section('content')

<div class="container-fluid">

    <!-- ENCABEZADO -->
    <div class="mb-4">
        <h3 class="fw-bold">Bienvenido al sistema SIS-CER EMI</h3>

        <p class="text-muted">
            Desde este panel podrás consultar las notificaciones
            relacionadas con tus solicitudes de Certificado de
            Estudiante Regular y conocer las actualizaciones de tus trámites.
        </p>
    </div>

    <!-- RESUMEN -->
    <div class="row g-3 mb-4">

        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Total de notificaciones</h6>

                    <h3 class="fw-bold">
                        {{ $notificaciones->count() }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Notificaciones pendientes</h6>

                    <h3 class="fw-bold text-primary">
                        {{ $notificaciones->where('leida', false)->count() }}
                    </h3>
                </div>
            </div>
        </div>

    </div>

    <!-- NOTIFICACIONES -->
    <div class="card shadow-sm border-0">

        <div class="card-header bg-white d-flex justify-content-between align-items-center">

            <h5 class="fw-bold mb-0">
                Mis notificaciones
            </h5>

            @if($notificaciones->where('leida', false)->count() > 0)

                <form action="{{ route('estudiante.notificaciones.todas-leidas') }}"
                      method="POST">

                    @csrf
                    @method('PUT')

                    <button type="submit" class="btn btn-sm btn-outline-primary">
                        Marcar todas como leídas
                    </button>

                </form>

            @endif

        </div>

        <div class="card-body">

            @forelse($notificaciones as $notificacion)

                <div class="border rounded p-3 mb-3
                    {{ $notificacion->leida ? 'bg-light' : 'border-primary' }}">

                    <div class="d-flex justify-content-between align-items-start">

                        <div class="me-3">

                            <div class="mb-2">

                                @if($notificacion->leida)
                                    <span class="badge bg-secondary">
                                        Leída
                                    </span>
                                @else
                                    <span class="badge bg-primary">
                                        Nueva
                                    </span>
                                @endif

                            </div>

                            <p class="mb-2">
                                {{ $notificacion->mensaje }}
                            </p>

                            <small class="text-muted">
                                {{ $notificacion->fecha_notificacion
                                    ? $notificacion->fecha_notificacion->format('d/m/Y H:i')
                                    : 'Fecha no disponible' }}
                            </small>

                            @if($notificacion->solicitud)

                                <div class="mt-2">
                                    <small>
                                        Código de solicitud:
                                        <strong>
                                            {{ $notificacion->solicitud->codigo_solicitud }}
                                        </strong>
                                    </small>
                                </div>

                            @endif

                        </div>

                        @if(!$notificacion->leida)

                            <form action="{{ route('estudiante.notificaciones.leida', $notificacion->id) }}"
                                  method="POST">

                                @csrf
                                @method('PUT')

                                <button type="submit"
                                        class="btn btn-sm btn-outline-success">
                                    Marcar leída
                                </button>

                            </form>

                        @endif

                    </div>

                </div>

            @empty

                <div class="text-center py-5">

                    <h5 class="text-muted">
                        No tienes notificaciones todavía.
                    </h5>

                    <p class="text-muted mb-0">
                        Aquí aparecerán las actualizaciones de tus solicitudes.
                    </p>

                </div>

            @endforelse

        </div>
    </div>

</div>

@endsection
