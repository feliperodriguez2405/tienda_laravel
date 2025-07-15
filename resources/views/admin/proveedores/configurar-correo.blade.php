@extends('layouts.app')

@section('title', 'Configurar Correo de Notificaciones')

@section('content')
<div class="container py-4">
    <h2 class="text-primary fw-bold mb-4">Configurar Correo de Notificaciones</h2>

    <!-- Mostrar alertas -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Mostrar estado actual del correo -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title">Estado Actual del Correo</h5>
            <p><strong>Correo Configurado:</strong> {{ $correo_notificaciones }}</p>
            <p><strong>Contraseña Configurada:</strong> {{ $password_configurada ? 'Sí' : 'No' }}</p>
            <p><strong>Estado de la Conexión SMTP:</strong> 
                @if ($smtpTestResult['success'])
                    <span class="text-success">Conexión exitosa</span>
                @else
                    <span class="text-danger">Conexión fallida: {{ $smtpTestResult['message'] }}</span>
                @endif
            </p>
        </div>
    </div>

    <!-- Formulario para configurar correo -->
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.proveedores.guardar-correo') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="correo_notificaciones" class="form-label">Correo para Notificaciones</label>
                    <input type="email" 
                           class="form-control @error('correo_notificaciones') is-invalid @enderror" 
                           id="correo_notificaciones" 
                           name="correo_notificaciones" 
                           value="{{ old('correo_notificaciones', $correo_notificaciones) }}" 
                           required>
                    @error('correo_notificaciones')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password_notificaciones" class="form-label">Contraseña</label>
                    <input type="password" 
                           class="form-control @error('password_notificaciones') is-invalid @enderror" 
                           id="password_notificaciones" 
                           name="password_notificaciones" 
                           required>
                    @error('password_notificaciones')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="{{ route('proveedores.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
@endsection