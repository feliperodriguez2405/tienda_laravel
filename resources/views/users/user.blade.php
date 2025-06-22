@extends('layouts.app2')

@section('title', 'Mi Perfil')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center fw-bold">Mi Perfil</h1>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="product-card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Información Personal</h5>
                </div>
                <div class="card-body text-center">
                    <p><strong>Nombre:</strong> {{ $user->name }}</p>
                    <p><strong>Correo Electrónico:</strong> {{ $user->email }}</p>
                    <p><strong>Fecha de Registro:</strong> {{ $user->created_at->format('d/m/Y') }}</p>
                    <a href="{{ route('user.settings') }}" class="btn btn-outline-success mt-3">
                        <i class="bi bi-gear me-1"></i> Editar Configuración
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection