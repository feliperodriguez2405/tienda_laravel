@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Encabezado con título y búsqueda -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h1 class="text-primary fw-bold mb-0">Gestión de Productos</h1>
            <p class="text-muted">Administra el inventario de tu supermercado</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('productos.create') }}" class="btn btn-success btn-sm me-2">
                <i class="bi bi-plus-lg me-1"></i>Agregar Producto
            </a>
        </div>
    </div>

    <!-- Mensaje de éxito -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Mensaje de error -->
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Filtros y búsqueda -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form action="{{ route('productos.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" 
                           name="search" 
                           class="form-control search-input" 
                           placeholder="Buscar por nombre..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="category" class="form-select">
                        <option value="">Todas las categorías</option>
                        @foreach($categorias ?? [] as $categoria)
                            <option value="{{ $categoria->id }}" {{ request('category') == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="stock" class="form-select">
                        <option value="">Todo el stock</option>
                        <option value="low" {{ request('stock') == 'low' ? 'selected' : '' }}>Bajo (0-10)</option>
                        <option value="medium" {{ request('stock') == 'medium' ? 'selected' : '' }}>Medio (11-50)</option>
                        <option value="high" {{ request('stock') == 'high' ? 'selected' : '' }}>Alto (>50)</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100 search-btn">
                        <i class="bi bi-search me-1"></i>Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Depuración: Mostrar número de productos -->
    @if (isset($productos))
        <div class="text-muted mb-2">
            Mostrando {{ $productos->count() }} de {{ $productos->total() }} productos ({{ $productos->perPage() }} por página)
        </div>
    @endif

    <!-- Lista de productos -->
    @if (!isset($productos) || $productos->isEmpty())
        <div class="alert alert-warning text-center shadow-sm">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>No hay productos disponibles con los filtros aplicados.</strong>
        </div>
    @else
        <div class="row">
            @foreach($productos as $producto)
                <div class="col-md-4 mb-4">
                    <div class="card product-card shadow-lg border-0 h-100">
                        <div class="position-relative">
                            @if($producto->stock <= 10)
                                <span class="badge bg-danger position-absolute top-0 end-0 m-2">
                                    Stock Bajo
                                </span>
                            @endif
                        </div>
                        <div class="card-body text-center d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="card-title text-dark fw-bold mb-2">{{ \Illuminate\Support\Str::limit($producto->nombre, 20) }}</h5>
                                <p class="card-text text-success fw-bold mb-1">${{ number_format($producto->precio, 2) }}</p>
                                <p class="card-text text-secondary mb-3">
                                    Stock: <span class="{{ $producto->stock <= 10 ? 'text-danger' : 'text-dark' }}">{{ $producto->stock }}</span>
                                </p>
                                <p class="card-text text-muted small">{{ $producto->categoria->nombre ?? 'Sin categoría' }}</p>
                            </div>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('productos.show', $producto->id) }}" 
                                   class="btn btn-info btn-sm" 
                                   title="Ver detalles">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('productos.edit', $producto->id) }}" 
                                   class="btn btn-warning btn-sm" 
                                   title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('productos.destroy', $producto->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-danger btn-sm" 
                                            title="Eliminar" 
                                            onclick="return confirm('¿Estás seguro de eliminar {{ addslashes($producto->nombre) }}?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-4">
            {{ $productos->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    @endif
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

    .product-card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .card-img-top {
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
    }

    .btn-sm {
        padding: 0.25rem 0.75rem;
        font-size: 0.875rem;
    }

    .form-select {
        border-radius: 20px;
    }

    .alert {
        border-radius: 8px;
    }
</style>
@endsection