@extends('layouts.app2')

@section('title', 'Productos Disponibles')

@section('content')
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