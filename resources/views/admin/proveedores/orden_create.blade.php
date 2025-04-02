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
                    <input type="datetime-local" name="fecha" id="fecha" class="form-control" value="{{ old('fecha', now()->format('Y-m-d\TH:i')) }}" required>
                </div>

                <div class="mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select name="estado" id="estado" class="form-select" required>
                        <option value="pendiente" {{ old('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="completada" {{ old('estado') == 'completada' ? 'selected' : '' }}>Completada</option>
                        <option value="cancelada" {{ old('estado') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Detalles</label>
                    <div id="detalles-container">
                        <div class="row mb-2 detalle-row" data-index="0">
                            <div class="col-md-4">
                                <input type="text" name="detalles[0][producto]" class="form-control" placeholder="Producto" value="{{ old('detalles.0.producto') }}" required>
                            </div>
                            <div class="col-md-2">
                                <input type="number" name="detalles[0][cantidad]" class="form-control" placeholder="Cantidad" value="{{ old('detalles.0.cantidad') }}" min="1" required>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="detalles[0][descripcion]" class="form-control" placeholder="Descripción (opcional)" value="{{ old('detalles.0.descripcion') }}">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger btn-sm remove-detalle" disabled>Eliminar</button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm" id="add-detalle">Agregar Detalle</button>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Guardar Orden</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let detalleIndex = 1;

    // Función para actualizar el estado de los botones "Eliminar"
    function updateRemoveButtons() {
        const rows = document.querySelectorAll('.detalle-row');
        rows.forEach((row, index) => {
            const removeButton = row.querySelector('.remove-detalle');
            if (rows.length === 1) {
                removeButton.disabled = true; // Deshabilita el botón si solo hay un detalle
            } else {
                removeButton.disabled = false; // Habilita el botón si hay más de un detalle
            }
        });
    }

    // Agregar un nuevo detalle
    document.getElementById('add-detalle').addEventListener('click', function() {
        const container = document.getElementById('detalles-container');
        const newRow = document.createElement('div');
        newRow.classList.add('row', 'mb-2', 'detalle-row');
        newRow.setAttribute('data-index', detalleIndex);
        newRow.innerHTML = `
            <div class="col-md-4">
                <input type="text" name="detalles[${detalleIndex}][producto]" class="form-control" placeholder="Producto" required>
            </div>
            <div class="col-md-2">
                <input type="number" name="detalles[${detalleIndex}][cantidad]" class="form-control" placeholder="Cantidad" min="1" required>
            </div>
            <div class="col-md-4">
                <input type="text" name="detalles[${detalleIndex}][descripcion]" class="form-control" placeholder="Descripción (opcional)">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-sm remove-detalle">Eliminar</button>
            </div>
        `;
        container.appendChild(newRow);
        detalleIndex++;
        updateRemoveButtons(); // Actualiza el estado de los botones después de agregar
    });

    // Eliminar un detalle
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-detalle') && !e.target.disabled) {
            e.target.closest('.detalle-row').remove();
            updateRemoveButtons(); // Actualiza el estado de los botones después de eliminar
        }
    });

    // Inicializar el estado de los botones al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        updateRemoveButtons();
    });
</script>
@endsection