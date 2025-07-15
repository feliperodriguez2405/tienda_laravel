@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Encabezado -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="text-primary fw-bold mb-0">Detalles del Producto</h2>
            <p class="text-muted">Información completa del producto</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Volver a Productos
            </a>
        </div>
    </div>

    <!-- Tarjeta del producto -->
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 product-card">
                <div class="row g-0">
                    <!-- Imagen -->
                    <div class="col-md-5">
                        <img src="{{ $producto->imagen ? asset('storage/'.$producto->imagen) : asset('images/placeholder.png') }}" 
                             class="img-fluid rounded-start w-100" 
                             alt="{{ $producto->nombre }}" 
                             style="height: 100%; object-fit: cover; max-height: 400px;">
                    </div>

                    <!-- Detalles -->
                    <div class="col-md-7">
                        <div class="card-body">
                            <h2 class="card-title text-primary fw-bold mb-3">{{ $producto->nombre }}</h2>
                            <p class="card-text mb-2"><strong>Descripción:</strong> {{ $producto->descripcion }}</p>
                            <p class="card-text mb-2"><strong>Código de barras:</strong> {{ $producto->codigo_barra ?? 'No asignado' }}</p>
                            @if ($producto->codigo_barra)
                                <div class="mb-3">
                                    <img src="data:image/png;base64,{{ $barcode }}" alt="Código de barras" class="img-fluid rounded" style="max-width: 200px; height: auto;">
                                </div>
                            @endif
                            <h4 class="text-success fw-bold mb-2">${{ number_format($producto->precio, 2) }}</h4>
                            <p class="text-secondary mb-2">Stock disponible: 
                                <span class="{{ $producto->stock <= 10 ? 'text-danger' : 'text-dark' }}">{{ $producto->stock }}</span>
                            </p>
                            <p class="mb-3"><strong>Categoría:</strong> {{ $producto->categoria->nombre ?? 'Sin categoría' }}</p>

                            <!-- Botones de acción -->
                            <div class="d-flex justify-content-between mt-4 gap-2">
                                <a href="{{ route('productos.edit', $producto->id) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil me-1"></i>Editar
                                </a>
                                <form action="{{ route('productos.destroy', $producto->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-danger btn-sm" 
                                            onclick="return confirm('¿Estás seguro de eliminar {{ addslashes($producto->nombre) }}?')">
                                        <i class="bi bi-trash me-1"></i>Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .product-card {
        border-radius: 12px;
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }

    .product-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .btn-sm {
        padding: 0.25rem 1rem;
        border-radius: 20px;
        transition: transform 0.2s ease-in-out;
    }

    .btn-sm:hover {
        transform: scale(1.05);
    }
</style>
@endsection