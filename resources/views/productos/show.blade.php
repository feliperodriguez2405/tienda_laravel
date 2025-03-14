@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="row g-0">
                    {{-- Imagen del Producto --}}
                    <div class="col-md-5">
                        <img src="{{ $producto->imagen ? asset('storage/'.$producto->imagen) : asset('images/placeholder.png') }}" 
                            class="img-fluid rounded-start w-100" alt="{{ $producto->nombre }}" 
                            style="height: 100%; object-fit: cover;">
                    </div>
                    
                    {{-- Detalles del Producto --}}
                    <div class="col-md-7">
                        <div class="card-body">
                            <h2 class="card-title text-primary fw-bold">{{ $producto->nombre }}</h2>
                            <p class="card-text"><strong>Descripción:</strong> {{ $producto->descripcion }}</p>
                            <h4 class="text-success fw-bold">${{ number_format($producto->precio, 2) }}</h4>
                            <p class="text-secondary">Stock disponible: <strong>{{ $producto->stock }}</strong></p>
                            <p><strong>Categoría:</strong> {{ $producto->categoria->nombre }}</p>

                            {{-- Botones de Acción --}}
                            <div class="d-flex justify-content-between mt-3">
                                <a href="{{ route('productos.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Volver
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
            </div>
        </div>
    </div>
</div>
@endsection
