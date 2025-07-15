@extends('layouts.app')

@section('title', 'Nueva Orden de Compra - ' . $proveedor->nombre)

@section('content')
<div class="container py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="mb-0 fw-bold">Nueva Orden de Compra - {{ $proveedor->nombre }}</h2>
            <p>Complete los detalles de la orden</p>
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
                            <div class="col-md-2">
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
                            <div class="col-md-2">
                                <input type="number" name="detalles[0][cantidad]" 
                                       class="form-control" 
                                       placeholder="Cantidad" 
                                       value="{{ old('detalles.0.cantidad') }}" 
                                       min="1" required>
                            </div>
                            <div class="col-md-2">
                                <input type="number" name="detalles[0][precio_compra]" 
                                       class="form-control" 
                                       placeholder="Precio Compra (COP)" 
                                       value="{{ old('detalles.0.precio_compra') }}" 
                                       step="0.01" min="0" required>
                            </div>
                            <div class="col-md-2">
                                <input type="number" name="detalles[0][precio_venta]" 
                                       class="form-control" 
                                       placeholder="Precio Venta (COP)" 
                                       value="{{ old('detalles.0.precio_venta') }}" 
                                       step="0.01" min="0" required>
                            </div>
                            <div class="col-md-2">
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
                <div class="col-md-2">
                    <input type="text" name="detalles[${index}][producto]" 
                           class="form-control producto-input" 
                           placeholder="Producto" 
                           list="productos-list" 
                           required>
                    <datalist id="productos-list">${productosList}</datalist>
                </div>
                <div class="col-md-2">
                    <input type="number" name="detalles[${index}][cantidad]" 
                           class="form-control" 
                           placeholder="Cantidad" 
                           min="1" 
                           required>
                </div>
                <div class="col-md-2">
                    <input type="number" name="detalles[${index}][precio_compra]" 
                           class="form-control" 
                           placeholder="Precio Compra (COP)" 
                           step="0.01" 
                           min="0" 
                           required>
                </div>
                <div class="col-md-2">
                    <input type="number" name="detalles[${index}][precio_venta]" 
                           class="form-control" 
                           placeholder="Precio Venta (COP)" 
                           step="0.01" 
                           min="0" 
                           required>
                </div>
                <div class="col-md-2">
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
                        <div class="col-md-2">
                            <input type="text" name="detalles[{{ $i }}][producto]" 
                                   class="form-control producto-input" 
                                   placeholder="Producto" 
                                   value="{{ $detalle['producto'] ?? '' }}"
                                   list="productos-list" 
                                   required>
                            <datalist id="productos-list">${productosList}</datalist>
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="detalles[{{ $i }}][cantidad]" 
                                   class="form-control" 
                                   placeholder="Cantidad" 
                                   value="{{ $detalle['cantidad'] ?? '' }}"
                                   min="1" 
                                   required>
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="detalles[{{ $i }}][precio_compra]" 
                                   class="form-control" 
                                   placeholder="Precio Compra (COP)" 
                                   value="{{ $detalle['precio_compra'] ?? '' }}"
                                   step="0.01" 
                                   min="0" 
                                   required>
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="detalles[{{ $i }}][precio_venta]" 
                                   class="form-control" 
                                   placeholder="Precio Venta (COP)" 
                                   value="{{ $detalle['precio_venta'] ?? '' }}"
                                   step="0.01" 
                                   min="0" 
                                   required>
                        </div>
                        <div class="col-md-2">
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



{{-- @extends('layouts.app')

@section('title', 'Nueva Orden de Compra - ' . $proveedor->nombre)

@section('content')
<div class="container py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h1 class="fw-bold mb-0">Nueva Orden de Compra</h1>
            <p class="text-muted">Proveedor: {{ $proveedor->nombre }}</p>
            <p class="text-muted">Hora actual en Bogotá: <span id="bogota-time"></span></p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.proveedores.ordenes.historial', $proveedor) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Volver al Historial
            </a>
        </div>
    </div>

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
    @if (session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Por favor, corrige los siguientes errores:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.proveedores.ordenes.store', $proveedor) }}" id="orden-form">
                @csrf

                <div class="mb-3">
                    <label for="fecha" class="form-label">Fecha <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="fecha" id="fecha" class="form-control @error('fecha') is-invalid @enderror"
                           value="{{ old('fecha', now()->format('Y-m-d\TH:i')) }}" required>
                    @error('fecha')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="estado" class="form-label">Estado <span class="text-danger">*</span></label>
                    <select name="estado" id="estado" class="form-select @error('estado') is-invalid @enderror" required>
                        <option value="pendiente" {{ old('estado', 'pendiente') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
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
                    <label class="form-label">Productos <span class="text-danger">*</span></label>
                    <div id="detalles-container">
                        <div class="row mb-3 detalle-row" data-index="0">
                            <div class="col-md-3">
                                <select name="detalles[0][producto_ids][]" class="form-select @error('detalles.0.producto_ids') is-invalid @enderror" multiple required>
                                    <option value="">Seleccione Productos</option>
                                    @foreach($productos as $producto)
                                        <option value="{{ $producto->id }}">{{ $producto->nombre }}</option>
                                    @endforeach
                                    <option value="new">Crear nuevo producto</option>
                                </select>
                                @error('detalles.0.producto_ids')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <input type="text" name="detalles[0][new_product_name]" class="form-control mt-2 new-product-input @error('detalles.0.new_product_name') is-invalid @enderror"
                                       placeholder="Nombre del nuevo producto" style="display: none;" value="{{ old('detalles.0.new_product_name') }}">
                                @error('detalles.0.new_product_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <select name="detalles[0][categoria_id]" class="form-select @error('detalles.0.categoria_id') is-invalid @enderror" required>
                                    <option value="">Seleccione Categoría</option>
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->id }}" {{ old('detalles.0.categoria_id') == $categoria->id ? 'selected' : '' }}>
                                            {{ $categoria->nombre }}
                                        </option>
                                    @endforeach
                                    <option value="new">Crear nueva categoría</option>
                                </select>
                                @error('detalles.0.categoria_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-1" id="new-category-container-0" style="display: {{ old('detalles.0.categoria_id') == 'new' ? 'block' : 'none' }};">
                                <input type="text" name="detalles[0][new_category_name]" class="form-control @error('detalles.0.new_category_name') is-invalid @enderror"
                                       placeholder="Nueva Categoría" value="{{ old('detalles.0.new_category_name') }}">
                                @error('detalles.0.new_category_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <input type="number" name="detalles[0][cantidad]" class="form-control @error('detalles.0.cantidad') is-invalid @enderror"
                                       placeholder="Cantidad" value="{{ old('detalles.0.cantidad') }}"
                                       min="1" step="1" required>
                                @error('detalles.0.cantidad')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <input type="number" name="detalles[0][precio_compra]" class="form-control @error('detalles.0.precio_compra') is-invalid @enderror"
                                       placeholder="Precio Compra" value="{{ old('detalles.0.precio_compra') }}"
                                       step="0.01" min="0" required>
                                @error('detalles.0.precio_compra')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-1">
                                <input type="number" name="detalles[0][precio_venta]" class="form-control @error('detalles.0.precio_venta') is-invalid @enderror"
                                       placeholder="Precio Venta" value="{{ old('detalles.0.precio_venta') }}"
                                       step="0.01" min="0" required>
                                @error('detalles.0.precio_venta')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-danger btn-sm remove-detalle" disabled>
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="add-detalle">
                        <i class="bi bi-plus-circle me-1"></i> Agregar Producto
                    </button>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send me-1"></i> Enviar Orden
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    (function() {
        console.log('Orden create script iniciado');

        // Bogotá Time Display
        function updateBogotaTime() {
            const options = { timeZone: 'America/Bogota', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
            const time = new Date().toLocaleTimeString('es-CO', options);
            document.getElementById('bogota-time').textContent = time;
        }
        updateBogotaTime();
        setInterval(updateBogotaTime, 1000);

        let index = 1;
        const container = document.getElementById('detalles-container');
        const addButton = document.getElementById('add-detalle');

        if (!container || !addButton) {
            console.error('Error: elementos container o addButton no encontrados');
            return;
        }

        function updateRemoveButtons() {
            const rows = container.querySelectorAll('.detalle-row');
            console.log('Actualizando botones, filas:', rows.length);
            rows.forEach(row => {
                const removeButton = row.querySelector('.remove-detalle');
                removeButton.disabled = rows.length === 1;
            });
        }

        function attachCategoryChangeListener(row) {
            const select = row.querySelector('select[name$="[categoria_id]"]');
            const newCategoryContainer = row.querySelector('div[id^="new-category-container-"]');
            if (select && newCategoryContainer) {
                select.addEventListener('change', function() {
                    console.log('Cambio en categoria_id, índice:', row.dataset.index, 'valor:', this.value);
                    newCategoryContainer.style.display = this.value === 'new' ? 'block' : 'none';
                });
            }
        }

        function attachProductChangeListener(row) {
            const select = row.querySelector('select[name$="[producto_ids][]"]');
            const newProductInput = row.querySelector('input[name$="[new_product_name]"]');
            if (select && newProductInput) {
                select.addEventListener('change', function() {
                    console.log('Cambio en producto_ids, índice:', row.dataset.index, 'valores:', this.value);
                    newProductInput.style.display = this.value.includes('new') ? 'block' : 'none';
                    if (this.value.includes('new') && newProductInput.value.trim()) {
                        saveNewProduct(newProductInput, row);
                    }
                });
                newProductInput.addEventListener('change', function() {
                    if (select.value.includes('new') && this.value.trim()) {
                        saveNewProduct(this, row);
                    }
                });
            }
        }

        function saveNewProduct(input, row) {
            const productName = input.value.trim();
            if (!productName) return;
            console.log('Guardando nuevo producto:', productName);
            fetch('{{ route('admin.productos.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ nombre: productName })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Producto guardado:', data.producto);
                    const select = row.querySelector('select[name$="[producto_ids][]"]');
                    const newOption = new Option(data.producto.nombre, data.producto.id);
                    select.appendChild(newOption);
                    select.value = [data.producto.id];
                    input.style.display = 'none';
                    input.value = '';
                } else {
                    console.error('Error al guardar producto:', data.error);
                    alert('Error al guardar el nuevo producto: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error en AJAX:', error);
                alert('Error al guardar el nuevo producto.');
            });
        }

        addButton.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Agregando fila, índice:', index);
            const newRow = document.createElement('div');
            newRow.className = 'row mb-3 detalle-row';
            newRow.dataset.index = index;
            newRow.innerHTML = `
                <div class="col-md-3">
                    <select name="detalles[${index}][producto_ids][]" class="form-select" multiple required>
                        <option value="">Seleccione Productos</option>
                        @foreach($productos as $producto)
                            <option value="{{ $producto->id }}">{{ $producto->nombre }}</option>
                        @endforeach
                        <option value="new">Crear nuevo producto</option>
                    </select>
                    <input type="text" name="detalles[${index}][new_product_name]" class="form-control mt-2 new-product-input"
                           placeholder="Nombre del nuevo producto" style="display: none;">
                </div>
                <div class="col-md-2">
                    <select name="detalles[${index}][categoria_id]" class="form-select" required>
                        <option value="">Seleccione Categoría</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                        @endforeach
                        <option value="new">Crear nueva categoría</option>
                    </select>
                </div>
                <div class="col-md-1" id="new-category-container-${index}" style="display: none;">
                    <input type="text" name="detalles[${index}][new_category_name]" class="form-control"
                           placeholder="Nueva Categoría">
                </div>
                <div class="col-md-2">
                    <input type="number" name="detalles[${index}][cantidad]" class="form-control"
                           placeholder="Cantidad" min="1" step="1" required>
                </div>
                <div class="col-md-2">
                    <input type="number" name="detalles[${index}][precio_compra]" class="form-control"
                           placeholder="Precio Compra" step="0.01" min="0" required>
                </div>
                <div class="col-md-1">
                    <input type="number" name="detalles[${index}][precio_venta]" class="form-control"
                           placeholder="Precio Venta" step="0.01" min="0" required>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger btn-sm remove-detalle">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            `;
            container.appendChild(newRow);
            attachCategoryChangeListener(newRow);
            attachProductChangeListener(newRow);
            index++;
            updateRemoveButtons();
        });

        container.addEventListener('click', function(e) {
            if (e.target.closest('.remove-detalle')) {
                console.log('Eliminando fila');
                e.target.closest('.detalle-row').remove();
                updateRemoveButtons();
            }
        });

        @if(old('detalles'))
            @php
                $oldDetalles = old('detalles');
            @endphp
            @foreach($oldDetalles as $i => $detalle)
                @if($i > 0)
                    console.log('Restaurando fila, índice:', {{ $i }});
                    const newRow = document.createElement('div');
                    newRow.className = 'row mb-3 detalle-row';
                    newRow.dataset.index = {{ $i }};
                    newRow.innerHTML = `
                        <div class="col-md-3">
                            <select name="detalles[${index}][producto_ids][]" class="form-select @error('detalles.{{ $i }}.producto_ids') is-invalid @enderror" multiple required>
                                <option value="">Seleccione Productos</option>
                                @foreach($productos as $producto)
                                    <option value="{{ $producto->id }}">{{ $producto->nombre }}</option>
                                @endforeach
                                <option value="new">Crear nuevo producto</option>
                            </select>
                            <input type="text" name="detalles[${index}][new_product_name]" class="form-control mt-2 new-product-input @error('detalles.{{ $i }}.new_product_name') is-invalid @enderror"
                                   placeholder="Nombre del nuevo producto" style="display: ${{{ json_encode(old("detalles.$i.producto_ids") && in_array('new', old("detalles.$i.producto_ids", [])) ? 'block' : 'none') }}};"
                                   value="${JSON.stringify({{ json_encode($detalle['new_product_name'] ?? '') }}).slice(1, -1)}">
                            @error('detalles.{{ $i }}.new_product_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-2">
                            <select name="detalles[${index}][categoria_id]" class="form-select @error('detalles.{{ $i }}.categoria_id') is-invalid @enderror" required>
                                <option value="">Seleccione Categoría</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                @endforeach
                                <option value="new">Crear nueva categoría</option>
                            </select>
                            @error('detalles.{{ $i }}.categoria_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-1" id="new-category-container-${index}" style="display: ${{{ json_encode(old("detalles.$i.categoria_id") == 'new' ? 'block' : 'none') }}};">
                            <input type="text" name="detalles[${index}][new_category_name]" class="form-control @error('detalles.{{ $i }}.new_category_name') is-invalid @enderror"
                                   placeholder="Nueva Categoría" value="${JSON.stringify({{ json_encode($detalle['new_category_name'] ?? '') }}).slice(1, -1)}">
                            @error('detalles.{{ $i }}.new_category_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="detalles[${index}][cantidad]" class="form-control @error('detalles.{{ $i }}.cantidad') is-invalid @enderror"
                                   placeholder="Cantidad" value="${{{ json_encode($detalle['cantidad'] ?? '') }}}"
                                   min="1" step="1" required>
                            @error('detalles.{{ $i }}.cantidad')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="detalles[${index}][precio_compra]" class="form-control @error('detalles.{{ $i }}.precio_compra') is-invalid @enderror"
                                   placeholder="Precio Compra" value="${{{ json_encode($detalle['precio_compra'] ?? '') }}}"
                                   step="0.01" min="0" required>
                            @error('detalles.{{ $i }}.precio_compra')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-1">
                            <input type="number" name="detalles[${index}][precio_venta]" class="form-control @error('detalles.{{ $i }}.precio_venta') is-invalid @enderror"
                                   placeholder="Precio Venta" value="${{{ json_encode($detalle['precio_venta'] ?? '') }}}"
                                   step="0.01" min="0" required>
                            @error('detalles.{{ $i }}.precio_venta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger btn-sm remove-detalle">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    `;
                    container.appendChild(newRow);
                    const select = newRow.querySelector('select[name$="[producto_ids][]"]');
                    select.value = {{{ json_encode($detalle['producto_ids'] ?? []) }}};
                    newRow.querySelector('select[name="detalles[${index}][categoria_id]"]').value = ${{{ json_encode($detalle['categoria_id'] ?? '') }}};
                    attachCategoryChangeListener(newRow);
                    attachProductChangeListener(newRow);
                    index = Math.max(index, {{ $i }} + 1);
                @endif
            @endforeach
            updateRemoveButtons();
        @endif

        document.querySelectorAll('select[name$="[producto_ids][]"]').forEach(select => {
            const row = select.closest('.detalle-row');
            const newProductInput = row.querySelector('.new-product-input');
            if (select && newProductInput) {
                if (select.value.includes('new')) {
                    newProductInput.style.display = 'block';
                }
                select.addEventListener('change', function() {
                    console.log('Cambio en producto_ids inicial, índice:', row.dataset.index, 'valores:', this.value);
                    newProductInput.style.display = this.value.includes('new') ? 'block' : 'none';
                    if (this.value.includes('new') && newProductInput.value.trim()) {
                        saveNewProduct(newProductInput, row);
                    }
                });
                newProductInput.addEventListener('change', function() {
                    if (select.value.includes('new') && this.value.trim()) {
                        saveNewProduct(this, row);
                    }
                });
            }
        });

        document.querySelectorAll('select[name$="[categoria_id]"]').forEach(select => {
            const row = select.closest('.detalle-row');
            const container = row.querySelector('div[id^="new-category-container-"]');
            if (select && container) {
                if (select.value === 'new') {
                    container.style.display = 'block';
                }
                select.addEventListener('change', function() {
                    console.log('Cambio en categoria_id inicial, índice:', row.dataset.index, 'valor:', this.value);
                    container.style.display = this.value === 'new' ? 'block' : 'none';
                });
            }
        });

        updateRemoveButtons();
        console.log('Inicialización completada');
    })();
</script>
@endsection --}}