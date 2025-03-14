@extends('layouts.app')

@section('title', 'Panel de Administración')

@section('content')
<div class="container">
    <!-- Barra superior con búsqueda -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="mb-0">Panel de Administración</h2>
        </div>
        <div class="col-md-6 text-end">
            <form class="d-flex justify-content-end" action="{{ route('productos.index') }}" method="GET">
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
    </div>

    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="list-group sidebar">
                <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action active">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
                <a href="{{ route('productos.index') }}" class="list-group-item list-group-item-action">
                    <i class="bi bi-box-seam me-2"></i> Gestionar Productos
                </a>
                <a href="{{ route('categorias.index') }}" class="list-group-item list-group-item-action">
                    <i class="bi bi-tags me-2"></i> Gestionar Categorías
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
                    <i class="bi bi-speedometer2 me-2"></i>
                    <h5 class="mb-0">Dashboard Administrativo</h5>
                </div>
                <div class="card-body">
                    <p class="lead mb-4">Bienvenido, <strong>{{ Auth::user()->name }}</strong>. Gestiona la tienda desde aquí.</p>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-info mb-3 option-card">
                                <div class="card-body d-flex align-items-center">
                                    <i class="bi bi-box-seam me-3 text-info" style="font-size: 2rem;"></i>
                                    <div>
                                        <h6 class="card-title">Gestionar Productos</h6>
                                        <p class="card-text">Administra el inventario de productos.</p>
                                        <a href="{{ route('productos.index') }}" class="btn btn-outline-info btn-sm">Ver Productos</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-warning mb-3 option-card">
                                <div class="card-body d-flex align-items-center">
                                    <i class="bi bi-tags me-3 text-warning" style="font-size: 2rem;"></i>
                                    <div>
                                        <h6 class="card-title">Gestionar Categorías</h6>
                                        <p class="card-text">Organiza las categorías de la tienda.</p>
                                        <a href="{{ route('categorias.index') }}" class="btn btn-outline-warning btn-sm">Ver Categorías</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <p class="text-muted text-center mb-0">© {{ date('Y') }} Supermercado Online | Panel de Administración</p>
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

    h2 {
        font-weight: 600;
        color: #0d6efd;
    }
</style>
@endsection