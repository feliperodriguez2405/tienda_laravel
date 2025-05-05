<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Panel de Cajero')</title>

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
            background-color: #e0f7fa;
            font-family: 'Arial', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Navbar */
        .navbar {
            padding: 1rem 0;
            background: #006064;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            transition: transform 0.2s ease-in-out;
            color: #e9ecef;
        }

        .navbar-brand:hover {
            transform: scale(1.05);
            color: #ffffff;
        }

        .nav-link {
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: color 0.2s ease-in-out;
            color: #e9ecef !important;
        }

        .nav-link:hover {
            color: #ffffff !important;
        }

        /* Main Content */
        main {
            flex: 1 0 auto;
        }

        /* Footer */
        footer {
            background: #006064;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            padding: 1.5rem 0;
        }

        footer p {
            margin: 0;
            font-size: 0.9rem;
            opacity: 0.9;
            color: #ffffff;
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
            <a class="navbar-brand" href="{{ route('cajero.dashboard') }}">
                <i class="bi bi-cash-stack me-2"></i>Panel de Cajero
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
                        <a class="nav-link" href="{{ route('cajero.sale') }}">
                            <i class="bi bi-cart-check me-1"></i>Registrar Venta
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cajero.transactions') }}">
                            <i class="bi bi-receipt me-1"></i>Transacciones
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cajero.close') }}">
                            <i class="bi bi-cash me-1"></i>Cierre de Caja
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
            <p>© {{ date('Y') }} Tienda D'jenny - Panel de Cajero</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>

    @stack('scripts')
</body>
</html>