@extends('layouts.app2')

@section('title', 'Productos Disponibles')

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
    
<div class="container">
    <h1 class="mb-4 text-center text-primary fw-bold">Productos Disponibles</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        @forelse($productos as $producto)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="{{ $producto->imagen ? asset('storage/' . $producto->imagen) : asset('images/placeholder.png') }}" 
                         class="card-img-top" 
                         alt="{{ $producto->nombre }}"
                         style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title text-dark fw-bold">{{ $producto->nombre }}</h5>
                        <p class="card-text text-muted">{{ $producto->descripcion }}</p>
                        <p class="card-text text-success fw-bold">${{ number_format($producto->precio, 2) }}</p>
                        <p class="card-text">Stock: <strong>{{ $producto->stock }}</strong></p>
                        <form action="{{ route('user.cart.add', $producto) }}" method="POST" class="d-flex align-items-center">
                            @csrf
                            <input type="number" name="cantidad" class="form-control me-2" value="1" min="1" max="{{ $producto->stock }}" style="width: 80px;">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-cart-plus"></i> Añadir al Carrito
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center text-muted">No hay productos disponibles en este momento.</p>
        @endforelse
    </div>
</div>
@endsection