@extends('layouts.app')

@section('title', 'Gestión de estudiantes | SIS-CER EMI')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold">Gestión de estudiantes</h3>
            <p class="text-muted mb-0">
                Registro y administración de estudiantes de la EMI.
            </p>
        </div>

        <button class="btn btn-primary"
                style="background-color: #12345a;"
                data-bs-toggle="modal"
                data-bs-target="#modalEstudiante">
            Registrar estudiante
        </button>
    </div>


    {{-- Tabla de estudiantes --}}

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <h5 class="fw-bold mb-3">Estudiantes registrados</h5>

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>RU</th>
                            <th>Estudiante</th>
                            <th>CI</th>
                            <th>Correo</th>
                            <th>Carrera</th>
                            <th>Semestre</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($estudiantes as $estudiante)

                            <tr>
                                <td>{{ $estudiante->ru }}</td>

                                <td>
                                    {{ $estudiante->nombres }}
                                    {{ $estudiante->apellido_paterno }}
                                    {{ $estudiante->apellido_materno }}
                                </td>

                                <td>{{ $estudiante->ci }}</td>

                                <td>{{ $estudiante->user->email }}</td>

                                <td>{{ $estudiante->carrera }}</td>

                                <td>{{ $estudiante->semestre ?? 'No registrado' }}</td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    No existen estudiantes registrados.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


{{-- Modal para registrar estudiante --}}

<div class="modal fade" id="modalEstudiante" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    Registrar nuevo estudiante
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>
            </div>

            <form action="{{ route('admin.estudiantes.store') }}" method="POST">

                @csrf

                <div class="modal-body">

                    <h6 class="fw-bold text-primary mb-3">
                        Datos de acceso
                    </h6>

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre de usuario</label>
                            <input type="text" name="name" class="form-control"
                                   value="{{ old('name') }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Correo electrónico</label>
                            <input type="email" name="email" class="form-control"
                                   value="{{ old('email') }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Contraseña</label>
                            <input type="password" name="password"
                                   class="form-control" required>
                        </div>

                    </div>

                    <hr>

                    <h6 class="fw-bold text-primary mb-3">
                        Datos personales y académicos
                    </h6>

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Registro Universitario (RU)</label>
                            <input type="text" name="ru" class="form-control"
                                   value="{{ old('ru') }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Carnet de Identidad (CI)</label>
                            <input type="text" name="ci" class="form-control"
                                   value="{{ old('ci') }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombres</label>
                            <input type="text" name="nombres" class="form-control"
                                   value="{{ old('nombres') }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Apellido paterno</label>
                            <input type="text" name="apellido_paterno"
                                   class="form-control"
                                   value="{{ old('apellido_paterno') }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Apellido materno</label>
                            <input type="text" name="apellido_materno"
                                   class="form-control"
                                   value="{{ old('apellido_materno') }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Carrera</label>
                            <input type="text" name="carrera" class="form-control"
                                   value="{{ old('carrera') }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Semestre</label>
                            <input type="number" name="semestre" class="form-control"
                                   value="{{ old('semestre') }}" min="1">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="telefono" class="form-control"
                                   value="{{ old('telefono') }}">
                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <button type="submit"
                            class="btn btn-primary"
                            style="background-color: #12345a;">
                        Guardar estudiante
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection
