@extends('layouts.app2')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center text-primary fw-bold">Lista de Productos</h1>

    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('productos.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Agregar Producto
        </a>
    </div>

    {{-- Verifica si hay productos --}}
    @if ($productos->isEmpty())
        <div class="alert alert-warning text-center">
            <strong>No hay productos disponibles.</strong>
        </div>
    @else
        <div class="row">
            @foreach($productos as $producto)
                <div class="col-md-4 mb-4">
                    <div class="card shadow-lg border-0">
                        <img src="{{ $producto->imagen ? asset('storage/'.$producto->imagen) : asset('images/placeholder.png') }}" 
                            class="card-img-top img-fluid" alt="{{ $producto->nombre }}" 
                            style="height: 200px; object-fit: cover;">

                        <div class="card-body text-center">
                            <h5 class="card-title text-dark fw-bold">{{ $producto->nombre }}</h5>
                            <p class="card-text text-success fw-bold">${{ number_format($producto->precio, 2) }}</p>
                            <p class="card-text text-secondary">Stock: <strong>{{ $producto->stock }}</strong></p>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('productos.show', $producto->id) }}" class="btn btn-info">
                                    <i class="fas fa-eye"></i> Ver
                                </a>
                                <a href="{{ route('productos.edit', $producto->id) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <form action="{{ route('productos.destroy', $producto->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar este producto?')">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
