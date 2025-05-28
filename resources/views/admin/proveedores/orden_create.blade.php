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
                        <option value="cancelado" {{ old('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Productos</label>
                    <div id="detalles-container">
                        @php $productos = \App\Models\Producto::all(); @endphp
                        <div class="row mb-2 detalle-row" data-index="0">
                            <div class="col-md-4">
                                <input type="text" name="detalles[0][producto]" 
                                       class="form-control producto-input" 
                                       placeholder="Producto" 
                                       value="{{ old('detalles.0.producto') }}" 
                                       list="productos-list" 
                                       required>
                                <datalist id="productos-list">
                                    @foreach($productos as $producto)
                                        <option value="{{ $producto->nombre }}">
                                    @endforeach
                                </datalist>
                            </div>
                            <div class="col-md-3">
                                <input type="number" name="detalles[0][cantidad]" 
                                       class="form-control" 
                                       placeholder="Cantidad" 
                                       value="{{ old('detalles.0.cantidad') }}" 
                                       min="1" required>
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="detalles[0][descripcion]" 
                                       class="form-control" 
                                       placeholder="Descripción (opcional)" 
                                       value="{{ old('detalles.0.descripcion') }}">
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
    // Ejecutar cuando todo esté cargado (DOM y recursos)
    window.addEventListener('load', function() {
        var index = 1;
        var container = document.getElementById('detalles-container');
        var addButton = document.getElementById('add-detalle');
        var productosList = document.getElementById('productos-list').innerHTML;

        // Verificar que los elementos existen
        if (!container) {
            console.error('Error: #detalles-container no encontrado');
            return;
        }
        if (!addButton) {
            console.error('Error: #add-detalle no encontrado');
            return;
        }

        // Función para actualizar botones "Eliminar"
        function updateRemoveButtons() {
            var rows = container.getElementsByClassName('detalle-row');
            for (var i = 0; i < rows.length; i++) {
                var removeButton = rows[i].querySelector('.remove-detalle');
                removeButton.disabled = rows.length === 1;
            }
        }

        // Agregar nueva fila
        addButton.addEventListener('click', function(e) {
            e.preventDefault();
            var newRow = document.createElement('div');
            newRow.className = 'row mb-2 detalle-row';
            newRow.setAttribute('data-index', index);
            newRow.innerHTML = `
                <div class="col-md-4">
                    <input type="text" name="detalles[${index}][producto]" 
                           class="form-control producto-input" 
                           placeholder="Producto" 
                           list="productos-list" 
                           required>
                    <datalist id="productos-list">${productosList}</datalist>
                </div>
                <div class="col-md-3">
                    <input type="number" name="detalles[${index}][cantidad]" 
                           class="form-control" 
                           placeholder="Cantidad" 
                           min="1" 
                           required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="detalles[${index}][descripcion]" 
                           class="form-control" 
                           placeholder="Descripción (opcional)">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-sm remove-detalle">Eliminar</button>
                </div>
            `;
            container.appendChild(newRow);
            index++;
            updateRemoveButtons();
        });

        // Eliminar fila
        container.addEventListener('click', function(e) {
            var target = e.target;
            if (target.classList.contains('remove-detalle') && !target.disabled) {
                target.closest('.detalle-row').remove();
                updateRemoveButtons();
            }
        });

        // Restaurar datos previos si hay errores de validación
        @if(old('detalles'))
            @foreach(old('detalles') as $i => $detalle)
                @if($i > 0)
                    var newRow{{ $i }} = document.createElement('div');
                    newRow{{ $i }}.className = 'row mb-2 detalle-row';
                    newRow{{ $i }}.setAttribute('data-index', {{ $i }});
                    newRow{{ $i }}.innerHTML = `
                        <div class="col-md-4">
                            <input type="text" name="detalles[{{ $i }}][producto]" 
                                   class="form-control producto-input" 
                                   placeholder="Producto" 
                                   value="{{ $detalle['producto'] ?? '' }}"
                                   list="productos-list" 
                                   required>
                            <datalist id="productos-list">${productosList}</datalist>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="detalles[{{ $i }}][cantidad]" 
                                   class="form-control" 
                                   placeholder="Cantidad" 
                                   value="{{ $detalle['cantidad'] ?? '' }}"
                                   min="1" 
                                   required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="detalles[{{ $i }}][descripcion]" 
                                   class="form-control" 
                                   placeholder="Descripción (opcional)"
                                   value="{{ $detalle['descripcion'] ?? '' }}">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger btn-sm remove-detalle">Eliminar</button>
                        </div>
                    `;
                    container.appendChild(newRow{{ $i }});
                    index = Math.max(index, {{ $i }} + 1);
                @endif
            @endforeach
            updateRemoveButtons();
        @endif

        // Inicializar estado de botones
        updateRemoveButtons();
    });
</script>
@endsection