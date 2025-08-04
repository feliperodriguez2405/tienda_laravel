@extends('layouts.app2')

@section('title', 'Carrito de Compras')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center fw-bold">Carrito de Compras</h1>

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
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (empty($cart))
        <p class="text-center">Tu carrito está vacío.</p>
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
                            <td>
                                @if ($producto->precio > 0)
                                    ${{ number_format($producto->precio, $producto->precio == floor($producto->precio) ? 0 : 2) }}
                                @else
                                    -
                                @endif
                            </td>
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
                            <td>
                                @if ($producto->precio > 0)
                                    ${{ number_format($producto->precio * $cart[$producto->id], ($producto->precio * $cart[$producto->id]) == floor($producto->precio * $cart[$producto->id]) ? 0 : 2) }}
                                @else
                                    -
                                @endif
                            </td>
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
                        <td class="fw-bold">
                            @php
                                $subtotal = $productos->sum(fn($p) => $p->precio * $cart[$p->id]);
                            @endphp
                            ${{ number_format($subtotal, $subtotal == floor($subtotal) ? 0 : 2) }}
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-end fw-bold">Impuesto (19% IVA):</td>
                        <td class="fw-bold">
                            @php
                                $iva = $subtotal * 0.19;
                            @endphp
                            ${{ number_format($iva, $iva == floor($iva) ? 0 : 2) }}
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-end fw-bold">Total:</td>
                        <td class="fw-bold">
                            @php
                                $total = $subtotal * 1.19;
                            @endphp
                            ${{ number_format($total, $total == floor($total) ? 0 : 2) }}
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Formulario de Checkout -->
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
                <div id="nequi-message" class="alert alert-info mt-2 d-none">
                    Por favor, realice el pago con Nequi al número 3152971513 mientras los trabajadores preparan su pedido.
                </div>
            </div>
            <div class="text-end">
                <button type="submit" class="btn btn-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Confirmar la orden y proceder al pago">
                    Confirmar Orden
                </button>
            </div>
        </form>
    @endif

    <div class="mt-4">
        <a href="{{ route('user.dashboard') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Seguir Comprando
        </a>
    </div>
</div>

@push('scripts')
<script>
    // Initialize Bootstrap tooltips
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Mostrar/Ocultar mensaje de Nequi según selección
        const metodoPagoSelect = document.getElementById('metodo_pago');
        const nequiMessage = document.getElementById('nequi-message');

        metodoPagoSelect.addEventListener('change', function () {
            if (this.value === 'nequi') {
                nequiMessage.classList.remove('d-none');
            } else {
                nequiMessage.classList.add('d-none');
            }
        });

        // Mostrar mensaje si Nequi está seleccionado al cargar la página
        if (metodoPagoSelect.value === 'nequi') {
            nequiMessage.classList.remove('d-none');
        }
    });
</script>
@endpush
@endsection