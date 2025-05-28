@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('content')
<div class="container py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="text-primary fw-semibold mb-0" style="font-size: 1.5rem;">Editar Usuario: {{ $user->name }}</h2>
            <p class="text-muted small mb-0">Modifica los detalles del usuario.</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-sm shadow-sm mb-3" role="alert">
            <i class="fas fa-exclamation-triangle me-1"></i>
            <strong>¡Ups!</strong>
            <ul class="mb-0 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card p-3 shadow-sm border-0">
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PUT')

            <div class="row g-2">
                <!-- Nombre -->
                <div class="col-md-6 mb-2">
                    <label for="name" class="form-label fw-medium small">Nombre</label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           class="form-control form-control-sm @error('name') is-invalid @enderror" 
                           value="{{ old('name', $user->name) }}" 
                           required>
                    @error('name')
                        <div class="invalid-feedback small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="col-md-6 mb-2">
                    <label for="email" class="form-label fw-medium small">Email</label>
                    <input type="email" 
                           name="email" 
                           id="email" 
                           class="form-control form-control-sm @error('email') is-invalid @enderror" 
                           value="{{ old('email', $user->email) }}" 
                           required>
                    @error('email')
                        <div class="invalid-feedback small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Contraseña -->
                <div class="col-md-6 mb-2">
                    <label for="password" class="form-label fw-medium small">Contraseña (opcional)</label>
                    <input type="password" 
                           name="password" 
                           id="password" 
                           class="form-control form-control-sm @error('password') is-invalid @enderror" 
                           placeholder="Dejar en blanco para no cambiar">
                    @error('password')
                        <div class="invalid-feedback small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirmar Contraseña -->
                <div class="col-md-6 mb-2">
                    <label for="password_confirmation" class="form-label fw-medium small">Confirmar Contraseña</label>
                    <input type="password" 
                           name="password_confirmation" 
                           id="password_confirmation" 
                           class="form-control form-control-sm">
                </div>

                <!-- Rol -->
                <div class="col-md-6 mb-2">
                    <label for="role" class="form-label fw-medium small">Rol</label>
                    <select name="role" 
                            id="role" 
                            class="form-select form-select-sm @error('role') is-invalid @enderror" 
                            required>
                        @foreach ($roles as $role)
                            <option value="{{ $role }}" {{ old('role', $user->role) === $role ? 'selected' : '' }}>
                                {{ ucfirst($role) }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')
                        <div class="invalid-feedback small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Botones -->
                <div class="col-12 d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-save me-1"></i> Guardar
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-times me-1"></i> Cancelar
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .card {
        border-radius: 8px;
        transition: transform 0.2s ease-in-out;
    }
    .card:hover {
        transform: translateY(-1px);
    }
    .form-control-sm, .form-select-sm {
        border-radius: 6px;
        font-size: 0.875rem;
        padding: 0.25rem 0.5rem;
    }
    .form-control-sm:focus, .form-select-sm:focus {
        box-shadow: 0 0 4px rgba(0, 123, 255, 0.3);
    }
    .btn-sm {
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-size: 0.875rem;
        transition: transform 0.2s ease-in-out;
    }
    .btn-sm:hover {
        transform: scale(1.03);
    }
    .alert-sm {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        border-radius: 6px;
    }
    .alert-sm ul {
        padding-left: 1rem;
    }
</style>

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endpush
@endsection