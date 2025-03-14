@extends('layouts.app2')

@section('title', 'Panel de Usuario')

@section('content')
<div class="container">
    <!-- Barra superior con búsqueda y carrito -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <form class="d-flex" action="{{ route('user.products') }}" method="GET">
                <input class="form-control me-2 search-input" 
                       type="search" 
                       name="search" 
                       placeholder="Buscar productos..." 
                       aria-label="Search">
                <button class="btn btn-outline-primary search-btn" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>
        <div class="col-md-6 text-end">
            <div class="cart-container">
                    <i class="bi bi-cart3"></i> Carrito
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ Auth::user()->cartItems ?? 0 }}
                        <span class="visually-hidden">items en carrito</span>
                    </span>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="list-group sidebar">
                <a href="{{ route('user.dashboard') }}" class="list-group-item list-group-item-action active">
                    <i class="bi bi-house-door me-2"></i> Inicio
                </a>
                <a href="{{ route('user.products') }}" class="list-group-item list-group-item-action">
                    <i class="bi bi-cart me-2"></i> Ver Productos
                </a>
                <a href="{{ route('user.orders') }}" class="list-group-item list-group-item-action">
                    <i class="bi bi-basket me-2"></i> Mis Pedidos
                </a>
                <a href="{{ route('user.settings') }}" class="list-group-item list-group-item-action">
                    <i class="bi bi-gear me-2"></i> Configuración
                </a>
                <a href="{{ route('logout') }}" 
                   class="list-group-item list-group-item-action text-danger" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>

        <!-- Contenido principal -->
        <div class="col-md-9">
            <div class="card main-card">
                <div class="card-header bg-primary text-white d-flex align-items-center">
                    <i class="bi bi-house-door me-2"></i>
                    <h5 class="mb-0">Bienvenido a Tu Supermercado</h5>
                </div>
                <div class="card-body">
                    <p class="lead mb-4">Hola, <strong>{{ Auth::user()->name }}</strong>. Gestiona tus compras fácilmente desde aquí.</p>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-success mb-3 option-card">
                                <div class="card-body d-flex align-items-center">
                                    <i class="bi bi-shop-window me-3 text-success" style="font-size: 2rem;"></i>
                                    <div>
                                        <h6 class="card-title">Productos Disponibles</h6>
                                        <p class="card-text">Explora y agrega productos a tu carrito.</p>
                                        <a href="{{ route('user.products') }}" class="btn btn-outline-success btn-sm">Ver Productos</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-info mb-3 option-card">
                                <div class="card-body d-flex align-items-center">
                                    <i class="bi bi-box-seam me-3 text-info" style="font-size: 2rem;"></i>
                                    <div>
                                        <h6 class="card-title">Mis Pedidos</h6>
                                        <p class="card-text">Consulta el estado de tus pedidos.</p>
                                        <a href="{{ route('user.orders') }}" class="btn btn-outline-info btn-sm">Ver Pedidos</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <p class="text-muted text-center mb-0">© {{ date('Y') }} Supermercado Ipiranga | Panel de Usuario</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Estilos personalizados */
    .search-input {
        border-radius: 20px;
        transition: box-shadow 0.2s ease-in-out;
    }

    .search-input:focus {
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }

    .search-btn {
        border-radius: 20px;
        transition: transform 0.2s ease-in-out;
    }

    .search-btn:hover {
        transform: scale(1.05);
    }

    .cart-btn {
        transition: transform 0.2s ease-in-out;
    }

    .cart-btn:hover {
        transform: scale(1.05);
    }

    .sidebar {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .list-group-item {
        transition: background-color 0.2s ease-in-out;
    }

    .list-group-item:hover {
        background-color: #f8f9fa;
    }

    .main-card {
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s ease-in-out;
    }

    .main-card:hover {
        transform: translateY(-2px);
    }

    .option-card {
        transition: transform 0.2s ease-in-out;
    }

    .option-card:hover {
        transform: translateY(-2px);
    }

    .btn-sm {
        padding: 0.25rem 1rem;
    }
</style>
@endsection