@extends('layouts.app')

@section('title', 'Historial de Compras - ' . $proveedor->nombre)

@section('content')
<div class="container py-5">
    <div class="row mb-5 align-items-center">
        <div class="col-md-6">
            <h2 class="mb-1">Historial de Compras - {{ $proveedor->nombre }}</h2>
            <p class="text-muted mb-0">Registro completo de tus órdenes (Estado: {{ ucfirst($proveedor->estado) }})</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.proveedores.ordenes.create', $proveedor) }}" class="btn btn-primary me-2 shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Nueva Orden
            </a>
            <a href="{{ route('proveedores.index') }}" class="btn btn-outline-secondary shadow-sm">Volver a Proveedores</a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3">Fecha</th>
                            <th class="py-3">Estado</th>
                            <th class="py-3">Detalles</th>
                            <th class="py-3">Notificación</th>
                            <th class="py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ordenes as $orden)
                            <tr class="border-light">
                                <td>{{ $orden->fecha->format('d/m/Y H:i') }}</td>
                                <td>
                                    <span class="badge estado-{{ $orden->estado }} py-2 px-3 text-dark">
                                        {{ ucfirst($orden->estado) }}
                                    </span>
                                </td>
                                <td>
                                    @if ($orden->detalles)
                                        <ul class="list-unstyled mb-0 text-muted">
                                            @foreach ($orden->detalles as $detalle)
                                                <li>
                                                    <i class="bi bi-box-seam me-1"></i>
                                                    {{ $detalle['producto'] }} ({{ $detalle['cantidad'] }})
                                                    @if (!empty($detalle['descripcion']))
                                                        - {{ $detalle['descripcion'] }}
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-muted">Sin detalles</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($proveedor->email && $proveedor->recibir_notificaciones)
                                        <span class="badge bg-success-light py-2 px-3 text-dark">Enviada</span>
                                    @else
                                        <span class="badge bg-secondary-light py-2 px-3 text-dark">No enviada</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.proveedores.ordenes.show', [$proveedor, $orden]) }}" 
                                       class="btn btn-sm btn-outline-info me-1">
                                        <i class="bi bi-eye"></i> Ver
                                    </a>
                                    <form action="{{ route('admin.proveedores.ordenes.destroy', [$proveedor, $orden]) }}" 
                                          method="POST" 
                                          class="d-inline" 
                                          onsubmit="return confirm('¿Estás seguro de eliminar esta orden?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i> Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox me-2"></i> No hay órdenes registradas
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="d-flex justify-content-center mt-4">
                {{ $ordenes->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Estilos de estados */
    .estado-pendiente {
        background-color: #ffeaa7;
    }
    .estado-procesando {
        background-color: #74b9ff;
    }
    .estado-enviado {
        background-color: #a29bfe;
    }
    .estado-entregado {
        background-color: #55efc4;
    }
    .estado-cancelado {
        background-color: #fab1a0;
    }

    /* Estilos de notificaciones */
    .bg-success-light {
        background-color: #b2f2bb !important;
    }
    .bg-secondary-light {
        background-color: #dfe4ea !important;
    }

    /* Asegurar visibilidad del texto */
    .badge {
        color: #212529 !important; /* Negro por defecto para buen contraste */
    }

    /* Mejoras generales */
    .btn {
        transition: all 0.3s ease;
    }
    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
    }
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
        transition: background-color 0.2s ease;
    }
    .card {
        background-color: #ffffff;
        transition: all 0.3s ease;
    }
    .card:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
    }
    .alert {
        border-radius: 0.5rem;
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
</style>
@endsection