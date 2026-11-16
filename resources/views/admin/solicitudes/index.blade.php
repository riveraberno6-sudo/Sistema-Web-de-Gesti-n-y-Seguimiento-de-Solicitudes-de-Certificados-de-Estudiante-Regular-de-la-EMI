
@extends('layouts.app')

@section('title', 'Gestión de solicitudes | SIS-CER EMI')

@section('content')

<div class="container-fluid">

    <!-- ENCABEZADO -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold">Gestión de solicitudes</h3>
            <p class="text-muted mb-0">
                Registro y seguimiento de solicitudes de Certificado de Estudiante Regular.
            </p>
        </div>

        <button class="btn btn-primary"
                data-bs-toggle="modal"
                data-bs-target="#modalSolicitud">
            + Registrar solicitud
        </button>
    </div>

    <!-- MENSAJES -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Verifica los siguientes datos:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- TABLA DE SOLICITUDES -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Solicitudes registradas</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>Código</th>
                            <th>Estudiante</th>
                            <th>Fecha de solicitud</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($solicitudes as $solicitud)
                            <tr>
                                <td>
                                    <strong>
                                        {{ $solicitud->codigo_solicitud }}
                                    </strong>
                                </td>

                                <td>
                                    @if($solicitud->estudiante)
                                        {{ $solicitud->estudiante->nombres }}
                                        {{ $solicitud->estudiante->apellido_paterno }}
                                        {{ $solicitud->estudiante->apellido_materno }}
                                    @else
                                        No disponible
                                    @endif
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($solicitud->fecha_solicitud)->format('d/m/Y') }}
                                </td>

                                <td>
                                    @php
                                        $colores = [
                                            'Recibida' => 'secondary',
                                            'En revisión' => 'warning',
                                            'Admitida' => 'info',
                                            'Rechazada' => 'danger',
                                            'En preparación' => 'primary',
                                            'Lista para recoger' => 'success',
                                            'Entregada' => 'dark',
                                        ];

                                        $color = $colores[$solicitud->estado->nombre ?? ''] ?? 'secondary';
                                    @endphp

                                    <span class="badge bg-{{ $color }}">
                                        {{ $solicitud->estado->nombre ?? 'Sin estado' }}
                                    </span>
                                </td>

                                <td>
                                    <a href="{{ route('admin.solicitudes.show', $solicitud->id) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        Ver detalles
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5"
                                    class="text-center text-muted py-4">
                                    No existen solicitudes registradas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>

</div>

<!-- MODAL PARA REGISTRAR SOLICITUD -->
<div class="modal fade" id="modalSolicitud" tabindex="-1"
     aria-labelledby="modalSolicitudLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form action="{{ route('admin.solicitudes.store') }}"
                  method="POST">

                @csrf

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalSolicitudLabel">
                        Registrar nueva solicitud
                    </h5>

                    <button type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">
                            Seleccionar estudiante
                        </label>

                        <select name="estudiante_id"
                                class="form-select" required>

                            <option value="">
                                Seleccione un estudiante
                            </option>

                            @foreach($estudiantes as $estudiante)
                                <option value="{{ $estudiante->id }}"
                                    {{ old('estudiante_id') == $estudiante->id ? 'selected' : '' }}>

                                    {{ $estudiante->nombres }}
                                    {{ $estudiante->apellido_paterno }}
                                    {{ $estudiante->apellido_materno }}
                                    - RU: {{ $estudiante->ru }}

                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Fecha de solicitud
                        </label>

                        <input type="date"
                               name="fecha_solicitud"
                               class="form-control"
                               value="{{ old('fecha_solicitud', date('Y-m-d')) }}"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Observaciones
                        </label>

                        <textarea name="observaciones"
                                  class="form-control"
                                  rows="3"
                                  placeholder="Observaciones opcionales">{{ old('observaciones') }}</textarea>
                    </div>

                    <div class="alert alert-info mb-0">
                        Los documentos del estudiante se registrarán
                        posteriormente desde el detalle de la solicitud,
                        una vez recibidos presencialmente.
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <button type="submit" class="btn btn-primary">
                        Registrar solicitud
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

@if($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = new bootstrap.Modal(
                document.getElementById('modalSolicitud')
            );

            modal.show();
        });
    </script>
@endif

@endsection
