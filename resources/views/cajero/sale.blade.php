@extends('layouts.master')
@section('title', 'Registrar Venta')
@section('content')
<div class="container">
    <h1>Registrar Venta</h1>

    <!-- Barra de búsqueda -->
    <form action="{{ route('cajero.sale') }}" method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Buscar productos por nombre..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Buscar</button>
        </div>
    </form>

    <!-- Formulario de venta -->
    <form method="POST" action="{{ route('cajero.sale') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Seleccionar Productos</label>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($productos as $producto)
                        <tr>
                            <td>{{ $producto->nombre }}</td>
                            <td>${{ number_format($producto->precio, 2) }}</td>
                            <td>{{ $producto->stock }}</td>
                            <td>
                                <input type="number" name="cantidades[]" min="0" max="{{ $producto->stock }}" 
                                       class="form-control w-50 d-inline" value="0" 
                                       onchange="updateCheckbox(this, {{ $producto->id }})">
                                <input type="hidden" name="productos[]" value="{{ $producto->id }}" 
                                       class="producto-checkbox" disabled>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No se encontraron productos.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mb-3">
            <label class="form-label">Método de Pago</label>
            <select name="metodo_pago" class="form-control">
                <option value="efectivo">Efectivo</option>
                <option value="nequi">Nequi</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Registrar Venta</button>
    </form>
</div>

@push('scripts')
<script>
    function updateCheckbox(input, productoId) {
        const checkbox = input.nextElementSibling;
        checkbox.disabled = input.value <= 0;
        checkbox.checked = input.value > 0;
    }
</script>
@endpush
@endsection