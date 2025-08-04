@extends('layouts.app2')

@section('title', 'Confirmar Pago')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center text-primary fw-bold">Confirmar Pago</h1>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
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
                        <td>
                            @if ($producto->precio > 0)
                                ${{ number_format($producto->precio, $producto->precio == floor($producto->precio) ? 0 : 2) }}
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $cart[$producto->id] }}</td>
                        <td>
                            @if ($producto->precio > 0)
                                ${{ number_format($producto->precio * $cart[$producto->id], ($producto->precio * $cart[$producto->id]) == floor($producto->precio * $cart[$producto->id]) ? 0 : 2) }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-end fw-bold">Subtotal:</td>
                    <td class="fw-bold">
                        @php
                            $subtotal = $productos->sum(fn($p) => $p->precio * $cart[$p->id]);
                        @endphp
                        ${{ number_format($subtotal, $subtotal == floor($subtotal) ? 0 : 2) }}
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="text-end fw-bold">Impuesto (19% IVA):</td>
                    <td class="fw-bold">
                        @php
                            $iva = $subtotal * 0.19;
                        @endphp
                        ${{ number_format($iva, $iva == floor($iva) ? 0 : 2) }}
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="text-end fw-bold">Total:</td>
                    <td class="fw-bold">
                        @php
                            $total = $subtotal * 1.19;
                        @endphp
                        ${{ number_format($total, $total == floor($total) ? 0 : 2) }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    <form action="{{ route('user.cart.checkout') }}" method="POST" class="mt-4">
        @csrf
        <div class="mb-3">
            <label for="metodo_pago" class="form-label fw-bold">Método de Pago</label>
            <select name="metodo_pago" id="metodo_pago" class="form-select @error('metodo_pago') is-invalid @enderror" required>
                <option value="" disabled selected>Selecciona un método</option>
                <option value="efectivo" {{ old('metodo_pago') == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                <option value="nequi" {{ old('metodo_pago') == 'nequi' ? 'selected' : '' }}>Nequi</option>
            </select>
            @error('metodo_pago')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="text-end">
            <button type="submit" class="btn btn-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Confirmar la orden y proceder al pago">
                Confirmar Orden
            </button>
        </div>
    </form>

    <div class="mt-4">
        <a href="{{ route('user.cart') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver al Carrito
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