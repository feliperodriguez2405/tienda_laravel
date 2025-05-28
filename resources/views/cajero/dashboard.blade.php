@extends('layouts.app3')

@section('title', 'Panel de Cajero')

@section('content')
<div class="container">
    <!-- Barra superior con búsqueda y logout -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <form class="d-flex" action="{{ route('cajero.transactions') }}" method="GET">
                <input class="form-control me-2 search-input" 
                       type="search" 
                       name="search" 
                       placeholder="Buscar transacciones..." 
                       aria-label="Search">
                <button class="btn btn-outline-primary search-btn" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>
        
    </div>

    <!-- Contenido principal -->
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card main-card">
                <div class="card-header bg-primary text-white d-flex align-items-center">
                    <i class="bi bi-cash-stack me-2"></i>
                    <h5 class="mb-0">Panel de Cajero</h5>
                </div>
                <div class="card-body">
                    <p class="lead mb-4">Hola, <strong>{{ Auth::user()->name }}</strong>. Gestiona las operaciones de caja desde aquí.</p>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="card border-success mb-3 option-card">
                                <div class="card-body d-flex align-items-center">
                                    <i class="bi bi-cart-check me-3 text-success" style="font-size: 2rem;"></i>
                                    <div>
                                        <h6 class="card-title">Procesar Venta</h6>
                                        <p class="card-text">Registra una nueva venta.</p>
                                        <a href="{{ route('cajero.sale') }}" class="btn btn-outline-success btn-sm">Iniciar Venta</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-info mb-3 option-card">
                                <div class="card-body d-flex align-items-center">
                                    <i class="bi bi-receipt me-3 text-info" style="font-size: 2rem;"></i>
                                    <div>
                                        <h6 class="card-title">Ver Transacciones</h6>
                                        <p class="card-text">Consulta el historial de transacciones.</p>
                                        <a href="{{ route('cajero.transactions') }}" class="btn btn-outline-info btn-sm">Ver Transacciones</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-warning mb-3 option-card">
                                <div class="card-body d-flex align-items-center">
                                    <i class="bi bi-cash me-3 text-warning" style="font-size: 2rem;"></i>
                                    <div>
                                        <h6 class="card-title">Cierre de Caja</h6>
                                        <p class="card-text">Realiza el cierre diario de caja.</p>
                                        <a href="{{ route('cajero.close') }}" class="btn btn-outline-warning btn-sm">Cerrar Caja</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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