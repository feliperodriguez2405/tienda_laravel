@extends('layouts.app')

@section('title', 'Panel de Administración')

@section('content')
<div class="container py-4">
    <!-- Barra superior con búsqueda -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h1 class="mb-0 fw-bold">Panel de Administración</h1>
            <p class="lead mb-4">Bienvenido, <strong>{{ Auth::user()->name }}</strong></p>
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
                                        <p class="card-text">Administra el inventario de productos de la tienda.</p>
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
                                        <p class="card-text">Organiza las categorías de la tienda.</p>
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
@endsection