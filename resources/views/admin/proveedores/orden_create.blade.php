@extends('layouts.app')

@section('title', 'Registrar Orden de Compra')

@section('content')
<div class="container py-4">
    <h2 class="text-primary fw-bold mb-4">Registrar Orden de Compra para {{ $proveedor->nombre }}</h2>

    <div class="card shadow-sm">
        <div class="card-body">
            @if ($proveedor->email && $proveedor->recibir_notificaciones)
                <div class="alert alert-info">
                    Se enviará una notificación automática al proveedor ({{ $proveedor->email }}) tras registrar la orden.
                </div>
            @else
                <div class="alert alert-warning">
                    No se enviará notificación al proveedor porque no tiene email o no desea recibir notificaciones.
                </div>
            @endif

            <form action="{{ route('admin.proveedores.ordenes.store', $proveedor) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="fecha" class="form-label">Fecha</label>
                    <input type="datetime-local" class="form-control @error('fecha') is-invalid @enderror" id="fecha" name="fecha" required value="{{ old('fecha') }}">
                    @error('fecha')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="monto" class="form-label">Monto</label>
                    <input type="number" step="0.01" class="form-control @error('monto') is-invalid @enderror" id="monto" name="monto" required value="{{ old('monto') }}">
                    @error('monto')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select class="form-control @error('estado') is-invalid @enderror" id="estado" name="estado" required>
                        <option value="pendiente" {{ old('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="procesando" {{ old('estado') == 'procesando' ? 'selected' : '' }}>Procesando</option>
                        <option value="enviado" {{ old('estado') == 'enviado' ? 'selected' : '' }}>Enviado</option>
                        <option value="entregado" {{ old('estado') == 'entregado' ? 'selected' : '' }}>Entregado</option>
                        <option value="cancelado" {{ old('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                    @error('estado')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Detalles de Pedidos Nuevos</label>
                    <div id="detalles-container">
                        <div class="row mb-2 detalle-item">
                            <div class="col-md-4">
                                <input type="text" name="detalles[0][producto]" class="form-control" placeholder="Producto" value="{{ old('detalles.0.producto') }}">
                            </div>
                            <div class="col-md-2">
                                <input type="number" name="detalles[0][cantidad]" class="form-control" placeholder="Cantidad" min="1" value="{{ old('detalles.0.cantidad') }}">
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="detalles[0][descripcion]" class="form-control" placeholder="Descripción" value="{{ old('detalles.0.descripcion') }}">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger remove-detalle">Eliminar</button>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="add-detalle" class="btn btn-secondary">Agregar Producto</button>
                    @error('detalles.*')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Registrar Orden</button>
                <a href="{{ route('admin.proveedores.historial', $proveedor) }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>

<script>
    let detalleIndex = 1;
    document.getElementById('add-detalle').addEventListener('click', function() {
        const container = document.getElementById('detalles-container');
        const newItem = `
            <div class="row mb-2 detalle-item">
                <div class="col-md-4">
                    <input type="text" name="detalles[${detalleIndex}][producto]" class="form-control" placeholder="Producto">
                </div>
                <div class="col-md-2">
                    <input type="number" name="detalles[${detalleIndex}][cantidad]" class="form-control" placeholder="Cantidad" min="1">
                </div>
                <div class="col-md-4">
                    <input type="text" name="detalles[${detalleIndex}][descripcion]" class="form-control" placeholder="Descripción">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-detalle">Eliminar</button>
                </div>
            </div>`;
        container.insertAdjacentHTML('beforeend', newItem);
        detalleIndex++;
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-detalle')) {
            e.target.closest('.detalle-item').remove();
        }
    });
</script>
@endsection