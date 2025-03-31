@extends('layouts.app')

@section('title', 'Gestión de Proveedores')

@section('content')
<div class="container py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="mb-0 text-primary fw-bold">Gestión de Proveedores</h2>
            <p class="text-muted">Administra los proveedores del supermercado</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.proveedores.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Nuevo Proveedor
            </a>
            <a href="{{ route('admin.proveedores.configurar-correo') }}" class="btn btn-secondary">
                <i class="bi bi-envelope me-1"></i> Configurar Correo
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Dirección</th>
                        <th>Contrato Vence</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($proveedores as $proveedor)
                        <tr {{ $proveedor->fecha_vencimiento_contrato && $proveedor->fecha_vencimiento_contrato->lessThanOrEqualTo(now()->addDays(7)) && $proveedor->recibir_notificaciones ? 'class=bg-warning' : '' }}>
                            <td>{{ $proveedor->nombre ?? 'Sin nombre' }}</td>
                            <td>{{ $proveedor->telefono ?? 'N/A' }}</td>
                            <td>{{ $proveedor->email ?? 'N/A' }}</td>
                            <td>{{ $proveedor->direccion ?? 'N/A' }}</td>
                            <td>
                                {{ $proveedor->fecha_vencimiento_contrato ? $proveedor->fecha_vencimiento_contrato->format('d/m/Y') : 'No definido' }}
                                @if ($proveedor->fecha_vencimiento_contrato && $proveedor->fecha_vencimiento_contrato->lessThanOrEqualTo(now()->addDays(7)) && $proveedor->recibir_notificaciones)
                                    <span class="badge bg-danger">Próximo a vencer</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.proveedores.historial', $proveedor) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-clock-history"></i> Historial
                                </a>
                                <a href="{{ route('admin.proveedores.edit', $proveedor) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.proveedores.destroy', $proveedor) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar este proveedor?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection