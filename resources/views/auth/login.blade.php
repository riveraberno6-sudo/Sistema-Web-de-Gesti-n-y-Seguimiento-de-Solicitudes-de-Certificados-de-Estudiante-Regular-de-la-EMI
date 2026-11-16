
@extends('layouts.app')

@section('title', 'Iniciar sesión | SIS-CER EMI')

@section('content')

<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 85vh;">

        <div class="col-md-5 col-lg-4">

            <div class="card shadow border-0">

                <div class="card-body p-4">

                    <div class="text-center mb-4">
                        <h3 class="fw-bold" style="color: #12345a;">
                            SIS-CER EMI
                        </h3>

                        <p class="text-muted mb-0">
                            Sistema de Seguimiento de Certificados
                        </p>

                        <hr>
                        <h5 class="mt-3">Iniciar sesión</h5>
                    </div>

                    <form action="{{ route('login.post') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">
                                Correo electrónico
                            </label>

                            <input
                                type="email"
                                name="email"
                                id="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}"
                                placeholder="Ingrese su correo"
                                required
                                autofocus
                            >

                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                Contraseña
                            </label>

                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Ingrese su contraseña"
                                required
                            >

                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary" style="background-color: #12345a;">
                                Ingresar al sistema
                            </button>
                        </div>

                    </form>

                </div>

            </div>

            <p class="text-center text-muted mt-3 small">
                Escuela Militar de Ingeniería
            </p>

        </div>

    </div>
</div>

@endsection
