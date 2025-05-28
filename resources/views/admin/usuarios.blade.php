@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold">Lista de Usuarios</h1>
            <p class="text-muted">Estos son los usuarios registrados en el sistema.</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-users-cog"></i> Gestión Avanzada
        </a>
        <div class="d-flex justify-content-between align-items-center mb-4">
    
    <a href="{{ route('admin.users.create') }}" class="btn btn-success">
        <i class="fas fa-user-plus"></i> Agregar Usuario
    </a>
</div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Email</th>
                            <th scope="col">Rol</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge bg-{{ $user->role === 'admin' ? 'primary' : ($user->role === 'cajero' ? 'info' : 'secondary') }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No hay usuarios registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 10px;
        overflow: hidden;
    }
    .table th, .table td {
        vertical-align: middle;
    }
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }
    .badge {
        font-size: 0.9rem;
        padding: 0.4em 0.6em;
    }
    .btn-sm {
        padding: 0.25rem 0.75rem;
    }
    .btn-outline-primary {
        transition: all 0.2s ease-in-out;
    }
    .btn-outline-primary:hover {
        background-color: #007bff;
        color: white;
    }
</style>

<!-- Incluir Font Awesome para íconos (si no está en layouts.app) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endsection