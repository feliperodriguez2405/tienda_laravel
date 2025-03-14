@extends('layouts.app2')

@section('title', 'Productos Disponibles')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center">Productos Disponibles</h1>

    <div class="row">
        @foreach($productos as $producto)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="{{ asset('storage/' . $producto->imagen) }}" class="card-img-top" alt="{{ $producto->nombre }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $producto->nombre }}</h5>
                        <p class="card-text">{{ $producto->descripcion }}</p>
                        <p class="card-text"><strong>Precio:</strong> ${{ number_format($producto->precio, 2) }}</p>
                        <a href="#" class="btn btn-primary">Añadir al Carrito</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if($productos->isEmpty())
        <p class="text-center text-muted">No hay productos disponibles en este momento.</p>
    @endif
</div>
@endsection
