@extends('layouts.app2')

@section('title', 'Mis Ordenes')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center fw-bold">Mis Ordenes</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if ($ordenes->isEmpty())
        <p class="text-center text-muted">No tienes órdenes registradas.</p>
    @else
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Método de Pago</th>
                        <th>Detalles</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ordenes as $orden)
                        <tr>
                            <td>{{ $orden->id }}</td>
                            <td>{{ $orden->created_at->format('d/m/Y H:i') }}</td>
                            <td>${{ number_format($orden->total, 2) }}</td>
                            <td>{{ ucfirst($orden->estado) }}</td>
                            <td>{{ $orden->metodo_pago ? ucfirst($orden->metodo_pago) : 'N/A' }}</td>
                            <td>
                                <button class="btn btn-outline-primary btn-sm" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#detalleModal{{ $orden->id }}"
                                        data-bs-toggle="tooltip" 
                                        data-bs-placement="top" 
                                        title="Ver detalles de la orden">
                                    Ver Detalles
                                </button>
                            </td>
                            <td>
                                @if ($orden->estado === 'procesando' && $orden->metodo_pago === 'nequi')
                                    <form action="{{ route('order.cancel', $orden->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de cancelar este pedido? Se iniciará el proceso de devolución.');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-danger btn-sm">Cancelar</button>
                                    </form>
                                @elseif ($orden->estado === 'pendiente' && $orden->metodo_pago === 'efectivo')
                                    <form action="{{ route('order.cancel', $orden->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de cancelar este pedido? No se realizará el pedido.');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-danger btn-sm">Cancelar</button>
                                    </form>
                                @elseif ($orden->estado === 'cancelado')
                                    <button class="btn btn-danger btn-sm" disabled>Cancelado</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Paginación -->
        <div class="mt-4">
            {{ $ordenes->links() }}
        </div>
    @endif

    <div class="mt-4">
        <a href="{{ route('user.dashboard') }}" class="btn btn-success">
            <i class="bi bi-arrow-left"></i> Volver al Inicio
        </a>
    </div>

    <!-- Modals para detalles -->
    @foreach ($ordenes as $orden)
        <div class="modal fade" id="detalleModal{{ $orden->id }}" tabindex="-1" aria-labelledby="detalleModalLabel{{ $orden->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detalleModalLabel{{ $orden->id }}">Detalles de la Orden #{{ $orden->id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orden->detalles as $detalle)
                                    <tr>
                                        <td>{{ $detalle->producto->nombre }}</td>
                                        <td>{{ $detalle->cantidad }}</td>
                                        <td>${{ number_format($detalle->subtotal, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<script>
    // Initialize Bootstrap tooltips
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Mostrar alerta para pagos con Nequi al cancelar
        @if (session('nequi_cancel'))
            alert('En estos momentos el cajero o administrador está devolviendo el dinero. Si no ha recibido nada en unos minutos, por favor póngase en contacto con el local.');
        @endif

        // Mostrar alerta para cancelación de pago en efectivo
        @if (session('efectivo_cancel'))
            alert('El pedido ha sido cancelado. No se realizará el pedido.');
        @endif
    });
</script>
@endsection