@extends('layouts.app2')

@section('title', 'Confirmar Pago')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center text-primary fw-bold">Confirmar Pago</h1>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio Unitario</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productos as $producto)
                    <tr>
                        <td>{{ $producto->nombre }}</td>
                        <td>${{ number_format($producto->precio, 2) }}</td>
                        <td>{{ $cart[$producto->id] }}</td>
                        <td>${{ number_format($producto->precio * $cart[$producto->id], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-end fw-bold">Total:</td>
                    <td class="fw-bold">${{ number_format($total, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <form action="{{ route('user.process.checkout') }}" method="POST" class="mt-4">
        @csrf
        <div class="mb-3">
            <label for="metodo_pago" class="form-label fw-bold">Método de Pago</label>
            <select name="metodo_pago" id="metodo_pago" class="form-select" required>
                <option value="efectivo">Efectivo</option>
                <option value="nequi">Nequi</option>
            </select>
        </div>
        <div class="text-end">
            <button type="submit" class="btn btn-success">Confirmar Orden</button>
        </div>
    </form>

    <div class="mt-4">
        <a href="{{ route('user.cart') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver al Carrito
        </a>
    </div>
</div>
@endsection