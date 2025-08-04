@extends('layouts.app')

@section('title', 'Gestión de Pedidos')

@section('content')
<div class="container py-4">
    <h1 class="fw-bold mb-0">Gestión de Pedidos</h1>
    <p>Visualiza y administra los pedidos en tiempo real.</p>

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

    <!-- Search form for filtering by client name, order ID, and status -->
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.pedidos') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="client_name" class="form-label">Nombre del Cliente</label>
                    <input type="text" name="client_name" id="client_name" class="form-control" value="{{ request('client_name') }}" placeholder="Buscar por nombre">
                </div>
                <div class="col-md-3">
                    <label for="order_id" class="form-label">ID del Pedido</label>
                    <input type="number" name="order_id" id="order_id" class="form-control" value="{{ request('order_id') }}" placeholder="Buscar por ID">
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Estado</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">Todos los estados</option>
                        <option value="pendiente" {{ request('status') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="procesando" {{ request('status') == 'procesando' ? 'selected' : '' }}>Procesando</option>
                        <option value="enviado" {{ request('status') == 'enviado' ? 'selected' : '' }}>Enviado</option>
                        <option value="entregado" {{ request('status') == 'entregado' ? 'selected' : '' }}>Entregado</option>
                        <option value="cancelado" {{ request('status') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Filtrar</button>
                    <a href="{{ route('admin.pedidos') }}" class="btn btn-secondary">Limpiar</a>
                </div>
            </form>
        </div>
    </div>
    <!-- End of search form -->

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
                                    <td>
                                        {{ $orden->user->name ?? 'Usuario no disponible' }}
                                        @if ($orden->user && $orden->user->trashed())
                                            <span class="badge bg-danger">Usuario eliminado</span>
                                        @endif
                                    </td>
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
                                        <form action="{{ route('admin.updateStatus', $orden) }}" method="POST" class="d-inline">
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
                                        <a href="{{ route('admin.invoice', $orden) }}" class="btn btn-sm btn-info">Factura (HTML)</a>
                                        <a href="{{ route('admin.generateInvoice', $orden) }}" class="btn btn-sm btn-success">Factura (PDF)</a>
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
                                            <form action="{{ route('admin.refund', $orden) }}" method="POST">
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

    <!-- Pagination links -->
    <div class="mt-3">
        {{ $ordenes->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
    <!-- End of pagination links -->
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