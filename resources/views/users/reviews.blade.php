@extends('layouts.app2')

@section('title', 'Reseñas de Productos')

@section('content')
<div class="container">
    <h2 class="mb-4">Reseñas de Productos</h2>

    <!-- Form to submit a new review -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Escribir una Reseña</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('user.reviews.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="producto_id" class="form-label">Producto</label>
                    <select name="producto_id" id="producto_id" class="form-select" required>
                        <option value="">Selecciona un producto</option>
                        @foreach ($productos as $producto)
                            <option value="{{ $producto->id }}">{{ $producto->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="calificacion" class="form-label">Calificación</label>
                    <select name="calificacion" id="calificacion" class="form-select" required>
                        <option value="">Selecciona una calificación</option>
                        <option value="1">1 estrella</option>
                        <option value="2">2 estrellas</option>
                        <option value="3">3 estrellas</option>
                        <option value="4">4 estrellas</option>
                        <option value="5">5 estrellas</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="comentario" class="form-label">Comentario (opcional)</label>
                    <textarea name="comentario" id="comentario" class="form-control" rows="4"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Enviar Reseña</button>
            </form>
        </div>
    </div>

    <!-- List of reviews -->
    <h3 class="mb-3">Reseñas Existentes</h3>
    @if ($reseñas->isEmpty())
        <p>No hay reseñas aún.</p>
    @else
        @foreach ($reseñas as $reseña)
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">{{ $reseña->producto->nombre }}</h5>
                    <p class="card-text">
                        <strong>Calificación:</strong> {{ str_repeat('⭐', $reseña->calificacion) }} ({{ $reseña->calificacion }}/5)
                    </p>
                    <p class="card-text">
                        <strong>Comentario:</strong> {{ $reseña->comentario ?? 'Sin comentario' }}
                    </p>
                    <p class="card-text">
                        <small class="text-muted">Por {{ $reseña->user->name }} el {{ $reseña->created_at->format('d/m/Y') }}</small>
                    </p>
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection