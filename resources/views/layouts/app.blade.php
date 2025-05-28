@extends('layouts.master')

@section('title', 'Panel de Administración')

<!-- Barra de navegación -->
@section('navbar')
    <a class="navbar-brand animate__animated animate__pulse" href="{{ route('admin.dashboard') }}">
        <i class="bi bi-gear-wide-connected me-2"></i>Administración
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
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="{{ route('admin.reportes') }}" role="button" aria-expanded="false">
                    <i class="bi bi-bar-chart me-1"></i>Reportes
                </a>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="#">Ultimos 7Dias</a></li>
                  <li><a class="dropdown-item" href="#">Top 5</a></li>
                  <li><a class="dropdown-item" href="#">Bajo Stock</a></li>
                  <li><a class="dropdown-item" href="#">Valor Total Inventario</a></li>
                  <li><a class="dropdown-item" href="#">Ganancias Total</a></li>
                  <li><a class="dropdown-item" href="#">Cierre Hoy</a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li><a class="dropdown-item" href="{{ route('admin.reportes') }}">General-Reports</a></li>
                </ul>
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
@endsection

@section('footer')
    <div class="container">
        <p>© {{ date('Y') }} Tienda D'jenny - Panel de Administración</p>
    </div>
@endsection

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>

    @stack('scripts')
