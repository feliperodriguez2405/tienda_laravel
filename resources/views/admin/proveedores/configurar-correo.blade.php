@extends('layouts.app')

@section('title', 'Configurar Correo de Notificaciones')

@section('content')
<div class="container py-4">
    <h2 class="text-primary fw-bold mb-4">Configurar Correo de Notificaciones</h2>

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
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="{{ route('proveedores.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
@endsection