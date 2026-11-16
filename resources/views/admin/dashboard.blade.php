
@extends('layouts.app')

@section('title', 'Panel administrativo | SIS-CER EMI')

@section('content')

<div class="container-fluid">

    <div class="mb-4">
        <h3 class="fw-bold">Panel administrativo</h3>
        <p class="text-muted">
            Sistema de Seguimiento de Certificados de Estudiante Regular
        </p>
    </div>

    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Gestión de solicitudes</h6>
                    <h4 class="fw-bold">Solicitudes</h4>
                    <p class="text-muted">
                        Registrar y administrar solicitudes de certificados.
                    </p>
                    <a href="{{ route('admin.solicitudes.index') }}"
                       class="btn btn-primary"
                       style="background-color: #12345a;">
                        Ver solicitudes
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Registro de estudiantes</h6>
                    <h4 class="fw-bold">Estudiantes</h4>
                    <p class="text-muted">
                        Registrar, consultar y actualizar estudiantes.
                    </p>
                    <a href="{{ route('admin.estudiantes.index') }}"
                       class="btn btn-primary"
                       style="background-color: #12345a;">
                        Ver estudiantes
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Seguimiento</h6>
                    <h4 class="fw-bold">Control de trámites</h4>
                    <p class="text-muted">
                        Revisar estados y controlar la entrega de certificados.
                    </p>
                    <a href="{{ route('admin.solicitudes.index') }}"
                       class="btn btn-primary"
                       style="background-color: #12345a;">
                        Consultar trámites
                    </a>
                </div>
            </div>
        </div>

    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h5 class="fw-bold">Bienvenido al sistema</h5>
            <p class="text-muted mb-0">
                Desde este panel podrás gestionar las solicitudes de
                Certificado de Estudiante Regular, registrar los documentos
                recibidos presencialmente y controlar la entrega de los certificados.
            </p>
        </div>
    </div>

</div>

@endsection
