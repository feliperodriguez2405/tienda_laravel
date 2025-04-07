@extends('layouts.app2')

@section('title', 'Carrito de Compras')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center text-primary fw-bold">Carrito de Compras</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
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
                            <td>{{ $cart[$producto->id] }}</td>
                            <td>${{ number_format($producto->precio * $cart[$producto->id], 2) }}</td>
                            <td>
                                <form action="{{ route('user.cart.remove', $producto) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end fw-bold">Total:</td>
                        <td class="fw-bold">${{ number_format($productos->sum(fn($p) => $p->precio * $cart[$p->id]), 2) }}</td>
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
        <a href="{{ route('user.products') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Seguir Comprando
        </a>
    </div>
</div>
@endsection