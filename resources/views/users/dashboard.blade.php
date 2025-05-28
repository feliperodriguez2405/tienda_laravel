@extends('layouts.app2')
@section('title', 'Dashboard de Usuario')
@section('content')
<div class="container">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <form class="d-flex align-items-center" action="{{ route('user.dashboard') }}" method="GET">
                <input class="form-control me-2 search-input" type="search" name="search" placeholder="Buscar productos..." aria-label="Buscar por nombre" value="{{ request('search') }}">
                <select class="form-select me-2 category-select" name="category" aria-label="Filtrar por categoría">
                    <option value="">Todas las categorías</option>
                    @foreach (\App\Models\Categoria::all() as $categoria)
                        <option value="{{ $categoria->id }}" {{ request('category') == $categoria->id ? 'selected' : '' }}>{{ $categoria->nombre }}</option>
                    @endforeach
                </select>
                <button class="btn btn-outline-primary search-btn" type="submit" aria-label="Buscar"><i class="bi bi-search"></i></button>
            </form>
        </div>
        <div class="col-md-4 text-end">
            <div class="cart-container position-relative">
                <a href="{{ route('user.cart') }}" class="btn btn-outline-dark cart-btn" aria-label="Ver carrito">
                    <i class="bi bi-cart3"></i> Carrito
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ session('cart') ? count(session('cart')) : 0 }}
                        <span class="visually-hidden">items en carrito</span>
                    </span>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card main-card">
                <div class="card-header bg-primary text-white d-flex align-items-center">
                    <i class="bi bi-house-door me-2"></i>
                    <h5 class="mb-0">Bienvenido a Tu Supermercado</h5>
                </div>
                <div class="card-body">
                    <p class="lead mb-4">Hola, <strong>{{ Auth::user()->name }}</strong>. Explora nuestros productos aquí.</p>

                    <div class="row">
                        @forelse ($productos as $producto)
                            <div class="col-md-4 col-sm-6 mb-4">
                                <div class="card product-card h-100">
                                    <div class="image-container">
                                        <img src="{{ $producto->imagen ? 'data:image/jpeg;base64,' . base64_encode($producto->imagen) : asset('images/placeholder.png') }}" 
                                             alt="{{ $producto->nombre }}" 
                                             class="product-img">
                                    </div>
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title">{{ Str::limit($producto->nombre, 20) }}</h5>
                                        <p class="card-text text-muted mb-2">{{ $producto->categoria->nombre ?? 'Sin categoría' }}</p>
                                        <p class="card-text price mb-2"><strong>${{ number_format($producto->precio, 2) }}</strong></p>
                                        <p class="card-text text-muted small">Stock: {{ $producto->stock }}</p>
                                        <form action="{{ route('user.cart.add', $producto) }}" method="POST" class="mt-auto d-flex align-items-center">
                                            @csrf
                                            <input type="number" name="cantidad" class="form-control me-2" value="1" min="1" max="{{ $producto->stock }}" style="width: 80px;">
                                            <button type="submit" class="btn btn-primary cart-button" aria-label="Agregar {{ $producto->nombre }} al carrito">
                                                <i class="bi bi-cart-plus me-1"></i> Agregar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center">
                                <p class="text-muted">No hay productos disponibles para los filtros seleccionados.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $productos->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .search-input, .category-select { 
        border-radius: 20px; 
        transition: box-shadow 0.2s ease-in-out; 
    }
    .search-input:focus, .category-select:focus { 
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); 
    }
    .search-btn, .cart-btn { 
        border-radius: 20px; 
        transition: transform 0.2s ease-in-out; 
    }
    .search-btn:hover, .cart-btn:hover { 
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
    .product-card {
        border: none;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s ease-in-out;
    }
    .product-card:hover {
        transform: translateY(-2px);
    }
    .image-container {
        height: 150px;
        overflow: hidden;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
        background-color: #f8f9fa;
    }
    .product-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .card-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #212529;
    }
    .price {
        font-size: 1.25rem;
        font-weight: 700;
        color: #212529;
    }
    .cart-button {
        border-radius: 20px;
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
        transition: transform 0.2s ease-in-out;
    }
    .cart-button:hover {
        transform: scale(1.05);
    }
</style>
@endsection