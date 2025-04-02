@extends('layouts.app')

@section('title', 'Panel de Administración')

@section('content')
<div class="container py-4">
    <!-- Barra superior con búsqueda -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="mb-0 text-primary fw-bold">Panel de Administración</h2>
            <p class="text-muted">Bienvenido, <strong>{{ Auth::user()->name }}</strong></p>
        </div>
        <div class="col-md-6 text-end">
            <form class="d-flex justify-content-end" action="{{ route('productos.index') }}" method="GET">
                <input class="form-control me-2 search-input" 
                       type="search" 
                       name="search" 
                       placeholder="Buscar productos..." 
                       aria-label="Search">
                <button class="btn btn-primary search-btn" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Contenido principal -->
    <div class="row">
        <div class="col-md-12">
            <div class="card main-card shadow-sm">
                <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-speedometer2 me-2 fs-4"></i>
                        <h5 class="mb-0">Dashboard Administrativo</h5>
                    </div>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="card border-info option-card shadow-sm h-100">
                                <div class="card-body d-flex align-items-center">
                                    <i class="bi bi-box-seam me-3 text-info" style="font-size: 2.5rem;"></i>
                                    <div>
                                        <h6 class="card-title fw-semibold">Gestionar Productos</h6>
                                        <p class="card-text text-muted">Administra el inventario de productos de la tienda.</p>
                                        <a href="{{ route('productos.index') }}" class="btn btn-outline-info btn-sm">Ver Productos</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-warning option-card shadow-sm h-100">
                                <div class="card-body d-flex align-items-center">
                                    <i class="bi bi-tags me-3 text-warning" style="font-size: 2.5rem;"></i>
                                    <div>
                                        <h6 class="card-title fw-semibold">Gestionar Categorías</h6>
                                        <p class="card-text text-muted">Organiza las categorías de la tienda.</p>
                                        <a href="{{ route('categorias.index') }}" class="btn btn-outline-warning btn-sm">Ver Categorías</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="my-4">
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Estilos mejorados */
    .container {
        max-width: 1200px; /* Más ancho para aprovechar el espacio */
    }

    .search-input {
        border-radius: 50px;
        border: 1px solid #ced4da;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 8px rgba(13, 110, 253, 0.2);
        outline: none;
    }

    .search-btn {
        border-radius: 50px;
        padding: 0.5rem 1.25rem;
        transition: all 0.3s ease;
    }

    .search-btn:hover {
        background-color: #0056b3;
        transform: scale(1.05);
    }

    .main-card {
        border-radius: 12px;
        border: none;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .main-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .option-card {
        border-radius: 10px;
        border-width: 2px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .option-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
    }

    .card-title {
        font-size: 1.25rem;
        margin-bottom: 0.5rem;
    }

    .card-text {
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }

    .btn-sm {
        padding: 0.35rem 1.25rem;
        border-radius: 20px;
        transition: all 0.3s ease;
    }

    .btn-sm:hover {
        transform: translateY(-2px);
    }

    h2 {
        font-size: 2rem;
        letter-spacing: -0.5px;
    }

    .card-header {
        border-radius: 12px 12px 0 0;
        padding: 1rem 1.5rem;
    }

    .shadow-sm {
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
</style>
@endsection