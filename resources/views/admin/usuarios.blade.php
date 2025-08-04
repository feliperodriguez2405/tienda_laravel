@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold">Lista de Usuarios</h1>
            <p class="text-muted">Estos son los usuarios registrados en el sistema.</p>
        </div>
        <div>
            <a href="{{ route('admin.users.create') }}" class="btn btn-success">
                <i class="fas fa-user-plus"></i> Agregar Usuario
            </a>
        </div>
    </div>

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
                                    <button class="btn btn-sm btn-outline-danger delete-user-btn" data-user-id="{{ $user->id }}" data-bs-toggle="modal" data-bs-target="#deleteUserModal{{ $user->id }}">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal para confirmación de eliminación -->
                            <div class="modal fade" id="deleteUserModal{{ $user->id }}" tabindex="-1" aria-labelledby="deleteUserModalLabel{{ $user->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteUserModalLabel{{ $user->id }}">Eliminar Usuario</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            ¿Estás seguro de que deseas eliminar al usuario {{ $user->name }}?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="button" class="btn btn-danger confirm-delete-btn" data-user-id="{{ $user->id }}">Confirmar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
        margin-right: 5px;
    }
    .btn-outline-primary, .btn-outline-danger {
        transition: all 0.2s ease-in-out;
    }
    .btn-outline-primary:hover {
        background-color: #007bff;
        color: white;
    }
    .btn-outline-danger:hover {
        background-color: #dc3545;
        color: white;
    }
</style>

<!-- Incluir Font Awesome para íconos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Handle delete button clicks
        document.querySelectorAll('.confirm-delete-btn').forEach(button => {
            button.addEventListener('click', function () {
                const userId = this.getAttribute('data-user-id');
                const modal = document.getElementById(`deleteUserModal${userId}`);
                const token = "{{ csrf_token() }}";

                fetch(`/admin/users/${userId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Content-Type': 'application/json',
                        'X-HTTP-Method-Override': 'DELETE'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove the user row from the table
                        const row = document.querySelector(`tr td:contains('${userId}')`).closest('tr');
                        row.remove();
                        // Show success alert
                        const alert = document.createElement('div');
                        alert.className = 'alert alert-success alert-dismissible fade show';
                        alert.innerHTML = `${data.success} <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`;
                        document.querySelector('.container').prepend(alert);
                    } else {
                        // Show error alert
                        const alert = document.createElement('div');
                        alert.className = 'alert alert-danger alert-dismissible fade show';
                        alert.innerHTML = `${data.error} <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`;
                        document.querySelector('.container').prepend(alert);
                    }
                    // Close the modal
                    const modalInstance = bootstrap.Modal.getInstance(modal);
                    modalInstance.hide();
                })
                .catch(error => {
                    console.error('Error:', error);
                    const alert = document.createElement('div');
                    alert.className = 'alert alert-danger alert-dismissible fade show';
                    alert.innerHTML = `Error al eliminar el usuario. <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`;
                    document.querySelector('.container').prepend(alert);
                    const modalInstance = bootstrap.Modal.getInstance(modal);
                    modalInstance.hide();
                });
            });
        });
    });
</script>
@endpush
@endsection