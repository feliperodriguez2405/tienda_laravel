@extends('layouts.app2')

@section('title', 'Carrito de Compras')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center fw-bold">Carrito de Compras</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if (empty($cart))
        <p class="text-center text-muted">Tu carrito está vacío.</p>
    @else
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio Unitario</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productos as $producto)
                        <tr>
                            <td>{{ $producto->nombre }}</td>
                            <td>${{ number_format($producto->precio, 2) }}</td>
                            <td>
                                <form action="{{ route('user.cart.update', $producto) }}" method="POST" class="d-flex align-items-center">
                                    @csrf
                                    <input type="number" 
                                           name="cantidad" 
                                           class="form-control me-2" 
                                           value="{{ $cart[$producto->id] }}" 
                                           min="1" 
                                           max="{{ $producto->stock }}" 
                                           style="width: 80px;"
                                           data-bs-toggle="tooltip" 
                                           data-bs-placement="top" 
                                           title="Actualiza la cantidad y presiona Enter">
                                    <button type="submit" class="btn btn-outline-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Actualizar cantidad">
                                        <i class="bi bi-arrow-repeat"></i>
                                    </button>
                                </form>
                            </td>
                            <td>${{ number_format($producto->precio * $cart[$producto->id], 2) }}</td>
                            <td>
                                <form action="{{ route('user.cart.remove', $producto) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Eliminar del carrito">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end fw-bold">Subtotal:</td>
                        <td class="fw-bold">${{ number_format($productos->sum(fn($p) => $p->precio * $cart[$p->id]), 2) }}</td>
                        <td></td>
                    </tr>
                    <!-- Optional: Add tax or discount -->
                    <tr>
                        <td colspan="3" class="text-end fw-bold">Impuesto (19% IVA):</td>
                        <td class="fw-bold">${{ number_format($productos->sum(fn($p) => $p->precio * $cart[$p->id]) * 0.19, 2) }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-end fw-bold">Total:</td>
                        <td class="fw-bold">${{ number_format($productos->sum(fn($p) => $p->precio * $cart[$p->id]) * 1.19, 2) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="text-end">
            <a href="{{ route('user.checkout') }}" class="btn btn-success">Proceder al Pago</a>
        </div>
    @endif

    <div class="mt-4">
        <a href="{{ route('user.dashboard') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Seguir Comprando
        </a>
    </div>
</div>

<script>
    // Initialize Bootstrap tooltips
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endsection