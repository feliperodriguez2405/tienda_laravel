@extends('layouts.app3')
@section('title', 'Cierre de Caja')
@section('content')
<div class="container">
    <h1>Cierre de Caja</h1>

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

    <!-- Resumen de ventas -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>Resumen de Ventas del Día</h5>
        </div>
        <div class="card-body">
            <p><strong>Total Ventas:</strong> ${{ number_format($totalVentas, 2) }}</p>
            <p><strong>Número de Transacciones:</strong> {{ $ordenes->count() }}</p>
            <p><strong>Transacciones de Cajero:</strong> {{ $ordenes->where('user_id', Auth::id())->count() }}</p>
            <p><strong>Transacciones de Clientes:</strong> {{ $ordenes->where('user_id', '!=', Auth::id())->count() }}</p>
            <h6>Métodos de Pago:</h6>
            <ul>
                @foreach ($metodosPago as $metodo => $count)
                    <li>{{ ucfirst($metodo) }}: {{ $count }} transacciones</li>
                @endforeach
                @if (empty($metodosPago))
                    <li>No hay transacciones completadas.</li>
                @endif
            </ul>
        </div>
    </div>

    <!-- Ventas por hora -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>Ventas por Hora</h5>
        </div>
        <div class="card-body">
            @if ($ventasPorHora->isNotEmpty())
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Hora</th>
                            <th>Total ($)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ventasPorHora as $venta)
                            <tr>
                                <td>{{ $venta['hora'] }}:00</td>
                                <td>{{ number_format($venta['total'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No hay ventas registradas para hoy.</p>
            @endif
        </div>
    </div>

    <!-- Productos más vendidos -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>Top 5 Productos Más Vendidos</h5>
        </div>
        <div class="card-body">
            @if ($productosMasVendidos->isNotEmpty())
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad Vendida</th>
                            <th>Total ($)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($productosMasVendidos as $producto)
                            <tr>
                                <td>{{ $producto->nombre }}</td>
                                <td>{{ $producto->cantidad_vendida }}</td>
                                <td>{{ number_format($producto->total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No hay productos vendidos hoy.</p>
            @endif
        </div>
    </div>

    <!-- Formulario para enviar cierre -->
    <form action="{{ route('cajero.close') }}" method="POST" id="close-form">
        @csrf
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i> Enviar Cierre de Caja</button>
    </form>
</div>

@push('styles')
<style>
    .card {
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .table {
        margin-bottom: 0;
    }
    .card-header {
        background-color: #f8f9fa;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        console.log('Close page loaded');

        // Manejar envío del cierre con confirmación
        document.getElementById('close-form').addEventListener('submit', (e) => {
            e.preventDefault();
            console.log('Close form submitted');
            Swal.fire({
                title: '¿Confirmar cierre de caja?',
                text: 'Se enviará el reporte al administrador.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, enviar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log('Close confirmed, submitting form');
                    e.target.submit();
                } else {
                    console.log('Close cancelled');
                }
            });
        });
    });
</script>
@endpush
@endsection