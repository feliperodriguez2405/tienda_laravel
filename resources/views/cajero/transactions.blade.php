@extends('layouts.app3')
@section('title', 'Transacciones de Clientes')
@section('content')
<div class="container">
    <h1>Transacciones de Clientes</h1>

    <!-- Botón para nueva venta -->
    <div class="mb-3">
        <a href="{{ route('cajero.sale') }}" class="btn btn-success"><i class="bi bi-cart-plus me-1"></i> Nueva Venta</a>
        <a href="{{ route('cajero.transactions.export') }}" class="btn btn-info"><i class="bi bi-download me-1"></i> Exportar a CSV</a>
    </div>

    <!-- Formulario de búsqueda y filtros -->
    <form action="{{ route('cajero.transactions') }}" method="GET" class="mb-4">
        <div class="row g-3 align-items-center">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Buscar por cliente..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="estado" class="form-control">
                    <option value="">Todos los estados</option>
                    <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="procesando" {{ request('estado') == 'procesando' ? 'selected' : '' }}>Procesando</option>
                    <option value="entregado" {{ request('estado') == 'entregado' ? 'selected' : '' }}>Entregado</option>
                    <option value="cancelado" {{ request('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="fecha_inicio" class="form-control" value="{{ request('fecha_inicio') }}" placeholder="Fecha inicio">
            </div>
            <div class="col-md-2">
                <input type="date" name="fecha_fin" class="form-control" value="{{ request('fecha_fin') }}" placeholder="Fecha fin">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Filtrar</button>
            </div>
        </div>
    </form>

    <!-- Mensajes de alerta -->
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            });
        </script>
    @endif
    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    icon: 'error',
                    title: '¡Error!',
                    text: '{{ session('error') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            });
        </script>
    @endif

    <!-- Tabla de transacciones -->
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Total</th>
                <th>Método de Pago</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th>Detalles</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($ordenes as $orden)
                <tr>
                    <td>{{ $orden->id }}</td>
                    <td>
                        @if ($orden->user)
                            {{ $orden->user->name }}
                            @if ($orden->user->trashed())
                                <span class="badge bg-warning text-dark">Eliminado</span>
                            @endif
                        @else
                            Usuario eliminado
                        @endif
                    </td>
                    <td>${{ number_format($orden->total, $orden->total == floor($orden->total) ? 0 : 2) }}</td>
                    <td>{{ isset($orden->metodo_pago) ? ucfirst($orden->metodo_pago) : 'N/A' }}</td>
                    <td>
                        <span class="badge bg-{{ $orden->estado == 'pendiente' ? 'warning' : ($orden->estado == 'procesando' ? 'info' : ($orden->estado == 'entregado' ? 'success' : ($orden->estado == 'cancelado' ? 'danger' : 'primary'))) }}">
                            {{ ucfirst($orden->estado) }}
                        </span>
                    </td>
                    <td>{{ $orden->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-info view-details" 
                                data-id="{{ $orden->id }}" 
                                data-estado="{{ $orden->estado }}"
                                data-details="{{ json_encode($orden->detalles->map(function($detalle) {
                                    return [
                                        'producto' => $detalle->producto->nombre,
                                        'cantidad' => $detalle->cantidad,
                                        'subtotal' => $detalle->subtotal
                                    ];
                                })) }}"
                                data-bs-toggle="modal" 
                                data-bs-target="#detailsModal">
                            Ver Detalles
                        </button>
                    </td>
                    <td>
                        <form action="{{ route('cajero.order.update', $orden->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <select name="estado" class="form-select form-select-sm d-inline w-auto" onchange="this.form.submit()">
                                <option value="pendiente" {{ $orden->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="procesando" {{ $orden->estado == 'procesando' ? 'selected' : '' }}>Procesando</option>
                                <option value="entregado" {{ $orden->estado == 'entregado' ? 'selected' : '' }}>Entregado</option>
                                <option value="cancelado" {{ $orden->estado == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                        </form>
                        <form action="{{ route('cajero.order.delete', $orden->id) }}" method="POST" class="d-inline delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                        </form>
                        @if ($orden->estado != 'entregado' && $orden->estado != 'cancelado' && !$orden->pagos()->where('estado', 'completado')->exists())
                            @if ($orden->metodo_pago == 'nequi')
                                <a href="{{ route('cajero.payment', $orden->id) }}" class="btn btn-sm btn-warning"><i class="bi bi-wallet2"></i> Pagar</a>
                            @else
                                <form action="{{ route('cajero.order.pay', $orden->id) }}" method="POST" class="d-inline pay-form">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-warning"><i class="bi bi-wallet2"></i> Pagar</button>
                                </form>
                            @endif
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">No se encontraron transacciones.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Paginación -->
    <div class="mt-4">
        {{ $ordenes->links() }}
    </div>

    <!-- Modal para detalles -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">Detalles de la Orden</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>ID Orden:</strong> <span id="modal-order-id"></span></p>
                    <p><strong>Estado:</strong> <span id="modal-order-estado"></span></p>
                    <div id="cancelled-alert" class="alert alert-danger d-none" role="alert">
                        Esta orden ha sido cancelada.
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="modal-details">
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }
    .form-select-sm {
        width: 120px;
    }
    .delete-form, .pay-form {
        display: inline-block;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        console.log('Transactions page loaded');

        // Format price function
        function formatPrice(value) {
            return value === Math.floor(value) ? `$${value}` : `$${value.toFixed(2)}`;
        }

        // Manejar eliminación con confirmación
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: 'Esta acción eliminará la orden permanentemente.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // Manejar pago con confirmación
        document.querySelectorAll('.pay-form').forEach(form => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                console.log('Pay form submitted');
                Swal.fire({
                    title: 'Confirmar pago',
                    text: '¿Confirmas que el pago en efectivo ha sido recibido?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        console.log('Payment confirmed, submitting form');
                        form.submit();
                    } else {
                        console.log('Payment cancelled');
                    }
                });
            });
        });

        // Mostrar detalles en el modal
        document.querySelectorAll('.view-details').forEach(button => {
            button.addEventListener('click', () => {
                console.log('View details clicked');
                const orderId = button.dataset.id;
                const estado = button.dataset.estado;
                const details = JSON.parse(button.dataset.details);
                document.getElementById('modal-order-id').textContent = orderId;
                document.getElementById('modal-order-estado').textContent = estado.charAt(0).toUpperCase() + estado.slice(1);
                const cancelledAlert = document.getElementById('cancelled-alert');
                if (estado === 'cancelado') {
                    cancelledAlert.classList.remove('d-none');
                } else {
                    cancelledAlert.classList.add('d-none');
                }
                const tbody = document.getElementById('modal-details');
                tbody.innerHTML = '';
                details.forEach(detail => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${detail.producto}</td>
                        <td>${detail.cantidad}</td>
                        <td>${formatPrice(parseFloat(detail.subtotal))}</td>
                    `;
                    tbody.appendChild(row);
                });
            });
        });
    });
</script>
@endpush
@endsection