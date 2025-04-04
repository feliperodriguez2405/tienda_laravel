@extends('layouts.app')

@section('title', 'Historial de Compras - ' . $proveedor->nombre)

@section('content')
<div class="container py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="mb-0 text-primary fw-bold">Historial de Compras - {{ $proveedor->nombre }}</h2>
            <p class="text-muted">Registro de órdenes de compra</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.proveedores.ordenes.create', $proveedor) }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Nueva Orden
            </a>
            <a href="{{ route('proveedores.index') }}" class="btn btn-secondary">Volver a Proveedores</a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('warning'))
        <div class="alert alert-warning">{{ session('warning') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Detalles</th>
                            <th>Notificación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ordenes as $orden)
                            <tr>
                                <td>{{ $orden->fecha->format('d/m/Y H:i') }}</td>
                                <td>
                                    <span class="badge 
                                        @if($orden->estado == 'pendiente') 
                                            bg-warning-dark text-dark fw-bold 
                                        @elseif($orden->estado == 'completada') 
                                            bg-success-dark text-white fw-bold 
                                        @elseif($orden->estado == 'cancelada') 
                                            bg-danger-dark text-white fw-bold 
                                        @endif">
                                        {{ ucfirst($orden->estado) }}
                                    </span>
                                </td>
                                <td>
                                    @if ($orden->detalles)
                                        <ul class="list-unstyled mb-0">
                                            @foreach ($orden->detalles as $detalle)
                                                <li>
                                                    {{ $detalle['producto'] }} ({{ $detalle['cantidad'] }})
                                                    @if (!empty($detalle['descripcion']))
                                                        - {{ $detalle['descripcion'] }}
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        Sin detalles
                                    @endif
                                </td>
                                <td>
                                    @if ($proveedor->email && $proveedor->recibir_notificaciones)
                                        <span class="badge bg-success">Enviada</span>
                                    @else
                                        <span class="badge bg-secondary">No enviada</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.proveedores.ordenes.show', [$proveedor, $orden]) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i> Ver
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No hay órdenes registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="d-flex justify-content-center mt-4">
                {{ $ordenes->links() }}
            </div>
        </div>
    </div>
</div>
@endsection