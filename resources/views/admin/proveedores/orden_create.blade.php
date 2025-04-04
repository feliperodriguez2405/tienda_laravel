@extends('layouts.app')

@section('title', 'Nueva Orden de Compra - ' . $proveedor->nombre)

@section('content')
<div class="container py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="mb-0 text-primary fw-bold">Nueva Orden de Compra - {{ $proveedor->nombre }}</h2>
            <p class="text-muted">Complete los detalles de la orden</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.proveedores.ordenes.historial', $proveedor) }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Volver al Historial
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.proveedores.ordenes.store', $proveedor) }}" id="orden-form">
                @csrf

                <div class="mb-3">
                    <label for="fecha" class="form-label">Fecha</label>
                    <input type="datetime-local" name="fecha" id="fecha" class="form-control" 
                           value="{{ old('fecha', now()->format('Y-m-d\TH:i')) }}" required>
                </div>

                <div class="mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select name="estado" id="estado" class="form-select" required>
                        <option value="pendiente" {{ old('estado', 'pendiente') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="procesando" {{ old('estado') == 'procesando' ? 'selected' : '' }}>Procesando</option>
                        <option value="enviado" {{ old('estado') == 'enviado' ? 'selected' : '' }}>Enviado</option>
                        <option value="entregado" {{ old('estado') == 'entregado' ? 'selected' : '' }}>Entregado</option>
                        <option value="cancelado" {{ old('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Producto</label>
                    <div id="detalles-container">
                        <div class="row mb-2 detalle-row" data-index="0">
                            <div class="col-md-4">
                                <input type="text" name="detalles[0][producto]" class="form-control" 
                                       placeholder="Producto" value="{{ old('detalles.0.producto') }}" required>
                            </div>
                            <div class="col-md-3">
                                <input type="number" name="detalles[0][cantidad]" class="form-control" 
                                       placeholder="Cantidad" value="{{ old('detalles.0.cantidad') }}" min="1" required>
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="detalles[0][descripcion]" class="form-control" 
                                       placeholder="Descripción (opcional)" value="{{ old('detalles.0.descripcion') }}">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger btn-sm remove-detalle" disabled>Eliminar</button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="add-detalle">Agregar Producto</button>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Enviar Orden</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM cargado, iniciando script');

        let detalleIndex = 1;

        function updateRemoveButtons() {
            const rows = document.querySelectorAll('.detalle-row');
            console.log('Actualizando botones, total de filas:', rows.length);
            rows.forEach((row) => {
                const removeButton = row.querySelector('.remove-detalle');
                removeButton.disabled = rows.length === 1;
            });
        }

        const addButton = document.getElementById('add-detalle');
        if (addButton) {
            console.log('Botón Agregar Detalle encontrado');
            addButton.addEventListener('click', function() {
                console.log('Clic en Agregar Detalle, índice actual:', detalleIndex);
                const container = document.getElementById('detalles-container');
                if (!container) {
                    console.error('Contenedor de detalles no encontrado');
                    return;
                }

                const newRow = document.createElement('div');
                newRow.classList.add('row', 'mb-2', 'detalle-row');
                newRow.setAttribute('data-index', detalleIndex);
                newRow.innerHTML = `
                    <div class="col-md-4">
                        <input type="text" name="detalles[${detalleIndex}][producto]" class="form-control" 
                               placeholder="Producto" required>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="detalles[${detalleIndex}][cantidad]" class="form-control" 
                               placeholder="Cantidad" min="1" required>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="detalles[${detalleIndex}][descripcion]" class="form-control" 
                               placeholder="Descripción (opcional)">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-sm remove-detalle">Eliminar</button>
                    </div>
                `;
                container.appendChild(newRow);
                detalleIndex++;
                updateRemoveButtons();
                console.log('Nuevo detalle agregado, índice actualizado a:', detalleIndex);
            });
        } else {
            console.error('Botón Agregar Detalle no encontrado');
        }

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-detalle')) {
                console.log('Clic en Eliminar Detalle');
                if (!e.target.disabled) {
                    const row = e.target.closest('.detalle-row');
                    if (row) {
                        row.remove();
                        console.log('Fila eliminada');
                        updateRemoveButtons();
                    } else {
                        console.error('No se encontró la fila para eliminar');
                    }
                } else {
                    console.log('Botón Eliminar desactivado, no se realiza acción');
                }
            }
        });

        updateRemoveButtons();
    });
</script>
@endsection