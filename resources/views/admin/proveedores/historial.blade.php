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
            <a href="{{ route('admin.proveedores') }}" class="btn btn-secondary">Volver a Proveedores</a>
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
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Monto</th>
                        <th>Estado</th>
                        <th>Detalles</th>
                        <th>Notificación</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ordenes as $orden)
                        <tr>
                            <td>{{ $orden->fecha->format('d/m/Y H:i') }}</td>
                            <td>{{ number_format($orden->monto, 2) }}</td>
                            <td>{{ ucfirst($orden->estado) }}</td>
                            <td>
                                @if ($orden->detalles)
                                    <ul>
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
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection