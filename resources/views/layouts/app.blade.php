<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel de Administración - Supermercado Online')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Estilos personalizados -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
        body {
            background-color: #e0f7fa;
            font-family: 'Arial', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            padding: 1rem 0;
            background: #006064;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            transition: transform 0.2s ease-in-out;
        }

        .navbar-brand:hover {
            transform: scale(1.05);
        }

        .nav-link {
            font-weight: 500;
            padding: 0.5rem 1rem;
            color: #e9ecef !important;
            transition: color 0.2s ease-in-out;
        }

        .nav-link:hover {
            color: #ffffff !important;
        }

        .logout-link {
            transition: transform 0.2s ease-in-out, color 0.2s ease-in-out;
        }

        .logout-link:hover {
            transform: scale(1.05);
            color: #dc3545 !important;
        }

        main {
            flex: 1 0 auto;
        }

        footer {
            background: #006064;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.15);
            padding: 1.5rem 0;
            color: #ffffff;
        }

        footer p {
            margin: 0;
            font-size: 0.9rem;
            opacity: 0.9;
        }

        @media (max-width: 768px) {
            .navbar-nav {
                text-align: center;
                padding-top: 1rem;
            }

            .nav-item {
                margin: 0.5rem 0;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-gear-wide-connected me-2"></i>Panel de Administración
            </a>
            <button class="navbar-toggler" 
                    type="button" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#navbarNav" 
                    aria-controls="navbarNav" 
                    aria-expanded="false" 
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('productos.index') }}">
                            <i class="bi bi-box-seam me-1"></i>Productos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('categorias.index') }}">
                            <i class="bi bi-tags me-1"></i>Categorías
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.users.index') }}">
                            <i class="bi bi-people me-1"></i>Usuarios
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.pedidos') }}">
                            <i class="bi bi-cart-check me-1"></i>Pedidos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('proveedores.index') }}">
                            <i class="bi bi-truck me-1"></i>Proveedores
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.reportes') }}">
                            <i class="bi bi-bar-chart me-1"></i>Reportes
                        </a>
                    </li>
                    <li class="nav-item">
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <a class="nav-link logout-link" 
                               href="{{ route('logout') }}"
                               onclick="event.preventDefault(); this.closest('form').submit();">
                                <i class="bi bi-box-arrow-right me-1"></i>Cerrar Sesión
                            </a>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container mt-4 mb-4">
        @yield('content')

        <!-- Alertas de productos que necesitan actualización -->
        @if (session('alerta_productos'))
            @foreach (session('alerta_productos') as $id => $alerta)
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    {{ $alerta['mensaje'] }} 
                    <a href="{{ $alerta['url'] }}" class="alert-link">Editar ahora</a>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endforeach
        @endif
    </main>

    <footer class="text-center py-3">
        <div class="container">
            <p>© {{ date('Y') }} Tienda D'jenny - Panel de Administración</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>

    @stack('scripts')
</body>
</html>