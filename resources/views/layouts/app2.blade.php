<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Supermercado Online')</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Estilos personalizados -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Navbar */
        .navbar {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
            background: linear-gradient(90deg, #0d6efd 0%, #5c9cff 100%);
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
            transition: color 0.2s ease-in-out;
        }

        .nav-link:hover {
            color: #e9ecef;
        }

        /* Main Content */
        main {
            flex: 1 0 auto;
        }

        /* Footer */
        footer {
            background: linear-gradient(90deg, #0d6efd 0%, #5c9cff 100%);
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            padding: 1.5rem 0;
        }

        footer p {
            margin: 0;
            font-size: 0.9rem;
            opacity: 0.9;
        }

        /* Botón Logout */
        .logout-link {
            transition: transform 0.2s ease-in-out, color 0.2s ease-in-out;
        }

        .logout-link:hover {
            transform: scale(1.05);
            color: #dc3545 !important;
        }

        /* Responsive adjustments */
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
    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('user.dashboard') }}">
                <i class="bi bi-shop me-2"></i>Supermercado Online
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
                        <a class="nav-link" href="{{ route('user.dashboard') }}">
                            <i class="bi bi-house-door me-1"></i>Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.products') }}">
                            <i class="bi bi-cart me-1"></i>Productos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.orders') }}">
                            <i class="bi bi-basket me-1"></i>Mis Pedidos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.settings') }}">
                            <i class="bi bi-gear me-1"></i>Configuración
                        </a>
                    </li>

                    @auth
                        <li class="nav-item">
                            <a class="nav-link text-danger logout-link" 
                               href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right me-1"></i>Cerrar Sesión
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i>Iniciar Sesión
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="bi bi-person-plus me-1"></i>Registrarse
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido dinámico -->
    <main class="container mt-4 mb-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="text-white text-center py-3">
        <div class="container">
            <p>© {{ date('Y') }} Supermercado Online - Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>

    @stack('scripts')
</body>
</html>