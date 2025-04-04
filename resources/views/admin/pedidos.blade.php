@extends('layouts.app')

@section('title', 'Gestión de Pedidos')

@section('content')
<div class="container py-4">
    <h1 class="text-primary fw-bold">Gestión de Pedidos</h1>
    <p class="text-muted">Visualiza y administra los pedidos en tiempo real.</p>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Lista de Pedidos</h5>
        </div>
        <div class="card-body">
            @if ($ordenes->isEmpty())
                <p class="text-muted">No hay pedidos registrados.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Dirección</th>
                                <th>Productos</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ordenes as $orden)
                                <tr>
                                    <td>{{ $orden->id }}</td>
                                    <td>{{ $orden->user->name ?? 'Usuario no disponible' }}</td>
                                    <td>{{ $orden->direccion ?? 'No especificada' }}</td>
                                    <td>
                                        <ul class="list-unstyled mb-0">
                                            @foreach ($orden->detalles as $detalle)
                                                <li>{{ $detalle->producto->nombre }} (x{{ $detalle->cantidad }})</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>${{ number_format($orden->total, 2) }}</td>
                                    <td>
                                        <form action="{{ route('admin.pedidos.updateStatus', $orden) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('POST')
                                            <select name="estado" class="form-select form-select-sm" onchange="this.form.submit()">
                                                <option value="pendiente" {{ $orden->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                                <option value="procesando" {{ $orden->estado == 'procesando' ? 'selected' : '' }}>Procesando</option>
                                                <option value="enviado" {{ $orden->estado == 'enviado' ? 'selected' : '' }}>Enviado</option>
                                                <option value="entregado" {{ $orden->estado == 'entregado' ? 'selected' : '' }}>Entregado</option>
                                                <option value="cancelado" {{ $orden->estado == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.pedidos.invoice', $orden) }}" class="btn btn-sm btn-info">Factura</a>
                                        @if ($orden->estado != 'cancelado')
                                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#refundModal{{ $orden->id }}">Reembolsar</button>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Modal para reembolso -->
                                <div class="modal fade" id="refundModal{{ $orden->id }}" tabindex="-1" aria-labelledby="refundModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="refundModalLabel">Reembolsar Pedido #{{ $orden->id }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('admin.pedidos.refund', $orden) }}" method="POST">
                                                @csrf
                                                @method('POST')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="motivo" class="form-label">Motivo del reembolso</label>
                                                        <textarea name="motivo" id="motivo" class="form-control" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-danger">Confirmar Reembolso</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .table th, .table td { vertical-align: middle; }
    .form-select-sm { width: auto; display: inline-block; }
    .btn-sm { margin-right: 5px; }
</style>

<script>
    // Recarga la página cada 30 segundos para simular tiempo real
    setInterval(() => {
        window.location.reload();
    }, 30000);
</script>
@endsection