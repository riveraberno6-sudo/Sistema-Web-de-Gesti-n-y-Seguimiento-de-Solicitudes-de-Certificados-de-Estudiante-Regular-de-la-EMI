<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIS-CER EMI')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
            font-family: Arial, sans-serif;
        }

        .navbar {
            background-color: #12345a;
        }

        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: #ffffff;
            border-right: 1px solid #dee2e6;
        }

        .sidebar .nav-link {
            color: #333;
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 5px;
        }

        .sidebar .nav-link:hover {
            background-color: #e9f0f8;
            color: #12345a;
        }

        .contenido {
            padding: 25px;
        }

        .titulo-sistema {
            font-weight: bold;
            letter-spacing: 1px;
        }
    </style>

    @yield('styles')
</head>
<body>

    <!-- BARRA SUPERIOR -->
    <nav class="navbar navbar-dark navbar-expand-lg">
        <div class="container-fluid">

            <a class="navbar-brand titulo-sistema" href="{{ route('dashboard') }}">
                SIS-CER EMI
            </a>

            @auth
                <div class="d-flex align-items-center">
                    <span class="text-white me-3">
                        {{ Auth::user()->name }}
                    </span>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm">
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            @endauth

        </div>
    </nav>

    <!-- CONTENIDO PARA USUARIOS AUTENTICADOS -->
    @auth
        <div class="container-fluid">
            <div class="row">

                <!-- MENÚ LATERAL -->
                <aside class="col-md-3 col-lg-2 sidebar p-3">

                    <h6 class="text-secondary mb-3">
                        MENÚ PRINCIPAL
                    </h6>

                    <div class="nav flex-column">

                        @if(Auth::user()->role === 'administrador')

                            <a href="{{ route('admin.solicitudes.index') }}" class="nav-link">
                                Solicitudes
                            </a>

                            <a href="{{ route('admin.estudiantes.index') }}" class="nav-link">
                                Estudiantes
                            </a>

                            <a href="{{ route('admin.solicitudes.index') }}" class="nav-link">
                                Control de trámites
                            </a>

                        @elseif(Auth::user()->role === 'estudiante')

                            <a href="{{ route('estudiante.notificaciones.index') }}" class="nav-link">
                                Notificaciones
                            </a>

                        @endif

                    </div>
                </aside>

                <!-- CONTENIDO PRINCIPAL -->
                <main class="col-md-9 col-lg-10 contenido">

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}

                            <button type="button"
                                    class="btn-close"
                                    data-bs-dismiss="alert">
                            </button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('content')

                </main>

            </div>
        </div>

    @else

        <!-- CONTENIDO PARA VISITANTES -->
        @yield('content')

    @endauth

    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    @yield('scripts')

</body>
</html>
