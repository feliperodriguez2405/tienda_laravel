@extends('layouts.app')

@section('title', 'Ver Categoría')

@section('content')
<div class="container my-5">
    <h1 class="fw-bold">Categoría: {{ $categoria->nombre }}</h1>
    <p class="text-muted">Detalles de la categoría.</p>

    <div class="card shadow-sm">
        <div class="card-body">
            <p><strong>ID:</strong> {{ $categoria->id }}</p>
            <p><strong>Nombre:</strong> {{ $categoria->nombre }}</p>
            <p><strong>Descripción:</strong> {{ $categoria->descripcion ?? 'Sin descripción' }}</p>
            <p><strong>Estado:</strong> 
                <span class="badge bg-{{ $categoria->estado === 'activo' ? 'success' : 'secondary' }}">
                    {{ ucfirst($categoria->estado ?? 'N/A') }}
                </span>
            </p>
            <a href="{{ route('categorias.index') }}" class="btn btn-outline-secondary mt-3">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
</div>
@endsection