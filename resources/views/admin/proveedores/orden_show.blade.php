<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Orden de Compra #{{ $orden->id }}</title>
    <link rel="icon" href="{{ asset('images/djenny.png') }}" type="image/x-icon">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <style>
        .card { border-radius: 10px; }
        .badge { font-size: 0.9em; padding: 0.5em 1em; }
        .table th, .table td { vertical-align: middle; }
        .form-control, .form-select { max-width: 200px; }
        .bg-info { background-color: #74b9ff; }
        .new-product-input, .new-category-container { display: none; }
        .descripcion { resize: vertical; min-height: 38px; max-height: 100px; width: 100%; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand animate__animated animate__pulse" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-gear-wide-connected me-2"></i>Administración
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('productos.index') }}">
                            <i class="bi bi-box-seam me-1"></i>Productos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('categorias.index') }}">
                            <i class="bi bi-tags me-1"></i>Categorías
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.users.index') }}">
                            <i class="bi bi-people me-1"></i>Usuarios
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.pedidos') }}">
                            <i class="bi bi-cart-check me-1"></i>Pedidos
                        </a>
                    </li>
                    <li class="nav-item dropdown d-flex align-items-center">
                        <a class="nav-link d-flex align-items-center pe-1" href="{{ route('admin.reportes') }}">
                            <i class="bi bi-bar-chart me-1"></i>Reportes
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownReportes">
                            <li><a class="dropdown-item" href="#">Últimos 7 Días</a></li>
                            <li><a class="dropdown-item" href="#">Top 5</a></li>
                            <li><a class="dropdown-item" href="#">Bajo Stock</a></li>
                            <li><a class="dropdown-item" href="#">Valor Total Inventario</a></li>
                            <li><a class="dropdown-item" href="#">Ganancias Total</a></li>
                            <li><a class="dropdown-item" href="#">Cierre Hoy</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('admin.reportes') }}">Reporte General</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('proveedores.index') }}">
                            <i class="bi bi-truck me-1"></i>Proveedores
                        </a>
                    </li>
                    <div class="btn-group">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link text-danger logout-link" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right me-1"></i>Cerrar Sesión
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        @endauth
                    </div>
                </ul>
                <div class="nav-item">
                    <i class="bi bi-moon-stars-fill theme-toggle ms-3" onclick="toggleTheme()"></i>
                </div>
            </div>
        </div>
    </nav>

    <main class="container mt-4 mb-4">
        <div class="container py-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h1 class="h4 mb-0">Orden de Compra #{{ $orden->id }}</h1>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-2"><strong class="text-muted">Proveedor:</strong> {{ $proveedor->nombre }}</p>
                            <p class="mb-2"><strong class="text-muted">Fecha:</strong> {{ $orden->fecha ? $orden->fecha->format('d/m/Y H:i') : 'Sin especificar' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Estado:</strong>
                                <span class="badge {{ $orden->estado === 'procesando' ? 'bg-info' : ($orden->estado === 'entregado' ? 'bg-success' : 'bg-secondary') }}">
                                    {{ ucfirst($orden->estado) }}
                                    @if ($orden->estado === 'procesando')
                                        <i class="bi bi-clock ms-1" title="En espera"></i>
                                    @endif
                                </span>
                            </p>
                            <p class="mb-2"><strong class="text-muted">Monto Total:</strong> {{ number_format($orden->monto ?? 0, 2, ',', '.') }} COP</p>
                        </div>
                    </div>

                    @foreach (session('alerta_productos', []) as $key => $alerta)
                        <div class="alert alert-warning alert-dismissible fade show" role="alert" id="alert-{{ $key }}">
                            {{ $alerta['mensaje'] }}.
                            <a href="{{ $alerta['url'] }}" class="alert-link">Editar producto</a>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endforeach
                    @foreach (session('alerta_precio', []) as $key => $alerta)
                        <div class="alert alert-warning alert-dismissible fade show" role="alert" id="alert-precio-{{ $key }}">
                            {{ $alerta['mensaje'] }}
                            <a href="{{ $alerta['url'] }}" class="alert-link">Actualizar ahora</a>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endforeach

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

                    <h3 class="h5 border-bottom pb-2 mb-3">Detalles de la Orden</h3>
                    <form method="POST" action="{{ route('admin.proveedores.ordenes.update', [$proveedor, $orden]) }}" id="orden-form">
                        @csrf
                        @method('PUT')
                        <div class="table-responsive">
                            <table class="table table-hover" id="detalles-table">
                                <thead class="table-light">
                                    <tr>
                                        <th>Producto</th>
                                        <th>Categoría</th>
                                        <th>Cantidad</th>
                                        <th>Stock Actual</th>
                                        <th>Precio Compra (COP)</th>
                                        <th>Porcentaje Ganancia</th>
                                        <th>Precio Venta (COP)</th>
                                        <th>Descripción</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody id="detalles-container">
                                    @foreach ($orden->detalles as $index => $detalle)
                                        <tr class="detalle-row" data-index="{{ $index }}">
                                            <td>
                                                <select name="detalles[{{ $index }}][producto_ids][]"
                                                        class="form-select product-select @error('detalles.{{ $index }}.producto_ids') is-invalid @enderror"
                                                        {{ $orden->estado !== 'procesando' ? 'disabled' : '' }} required>
                                                    <option value="">Seleccione Producto</option>
                                                    @foreach ($productos as $producto)
                                                        <option value="{{ $producto->id }}"
                                                                data-categoria-id="{{ $producto->categoria_id }}"
                                                                data-stock="{{ $producto->stock }}"
                                                                {{ in_array($producto->id, $detalle['producto_ids'] ?? []) ? 'selected' : '' }}>
                                                            {{ $producto->nombre }}
                                                        </option>
                                                    @endforeach
                                                    <option value="new">Crear nuevo producto</option>
                                                </select>
                                                @error('detalles.{{ $index }}.producto_ids')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <input type="text" name="detalles[{{ $index }}][new_product_name]"
                                                       class="form-control mt-2 new-product-input @error('detalles.{{ $index }}.new_product_name') is-invalid @enderror"
                                                       placeholder="Nombre del nuevo producto"
                                                       style="display: {{ in_array('new', $detalle['producto_ids'] ?? []) ? 'block' : 'none' }};"
                                                       value="{{ $detalle['new_product_name'] ?? '' }}">
                                                @error('detalles.{{ $index }}.new_product_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <select name="detalles[{{ $index }}][categoria_id]"
                                                        class="form-select categoria-select @error('detalles.{{ $index }}.categoria_id') is-invalid @enderror"
                                                        {{ $orden->estado !== 'procesando' ? 'disabled' : '' }}>
                                                    <option value="">Sin Categoría</option>
                                                    @foreach ($categorias as $categoria)
                                                        <option value="{{ $categoria->id }}"
                                                                {{ ($detalle['categoria_id'] ?? ($proveedor->categoria_id ?? '')) == $categoria->id ? 'selected' : '' }}>
                                                            {{ $categoria->nombre }}
                                                        </option>
                                                    @endforeach
                                                    <option value="new">Crear nueva categoría</option>
                                                </select>
                                                @error('detalles.{{ $index }}.categoria_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <div class="new-category-container" id="new-category-container-{{ $index }}"
                                                     style="display: {{ ($detalle['categoria_id'] ?? '') == 'new' ? 'block' : 'none' }};">
                                                    <input type="text" name="detalles[{{ $index }}][new_category_name]"
                                                           class="form-control @error('detalles.{{ $index }}.new_category_name') is-invalid @enderror"
                                                           placeholder="Nueva Categoría"
                                                           value="{{ $detalle['new_category_name'] ?? '' }}">
                                                    @error('detalles.{{ $index }}.new_category_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </td>
                                            <td>
                                                <input type="number" name="detalles[{{ $index }}][cantidad]"
                                                       value="{{ $detalle['cantidad'] ?? 1 }}"
                                                       class="form-control @error('detalles.{{ $index }}.cantidad') is-invalid @enderror"
                                                       min="1" step="1"
                                                       {{ $orden->estado !== 'procesando' ? 'disabled' : '' }} required>
                                                @error('detalles.{{ $index }}.cantidad')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="number" name="detalles[{{ $index }}][stock_actual]"
                                                       value="{{ ($detalle['producto_ids'][0] ?? '') && is_numeric($detalle['producto_ids'][0]) ? ($productos->find($detalle['producto_ids'][0])?->stock ?? 0) : 0 }}"
                                                       class="form-control"
                                                       readonly>
                                            </td>
                                            <td>
                                                <input type="number" name="detalles[{{ $index }}][precio_compra]"
                                                       value="{{ $detalle['precio_compra'] ?? 0 }}"
                                                       class="form-control precio-compra @error('detalles.{{ $index }}.precio_compra') is-invalid @enderror"
                                                       step="0.01" min="0" placeholder="Precio Compra"
                                                       required>
                                                @error('detalles.{{ $index }}.precio_compra')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <select name="detalles[{{ $index }}][porcentaje_ganancia]"
                                                        id="porcentaje_ganancia_{{ $index }}"
                                                        class="form-select form-select-sm porcentaje-ganancia @error('detalles.{{ $index }}.porcentaje_ganancia') is-invalid @enderror">
                                                    <option value="">Selecciona un porcentaje</option>
                                                    <option value="20" {{ ($detalle['porcentaje_ganancia'] ?? '') == '20' ? 'selected' : '' }}>20%</option>
                                                    <option value="25" {{ ($detalle['porcentaje_ganancia'] ?? '') == '25' ? 'selected' : '' }}>25%</option>
                                                    <option value="30" {{ ($detalle['porcentaje_ganancia'] ?? '') == '30' ? 'selected' : '' }}>30%</option>
                                                    <option value="40" {{ ($detalle['porcentaje_ganancia'] ?? '') == '40' ? 'selected' : '' }}>40%</option>
                                                    <option value="50" {{ ($detalle['porcentaje_ganancia'] ?? '') == '50' ? 'selected' : '' }}>50%</option>
                                                </select>
                                                @error('detalles.{{ $index }}.porcentaje_ganancia')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="number" name="detalles[{{ $index }}][precio_venta]"
                                                       value="{{ $detalle['precio_venta'] ?? 0 }}"
                                                       class="form-control precio-venta @error('detalles.{{ $index }}.precio_venta') is-invalid @enderror"
                                                       step="0.01" min="0" placeholder="Precio Venta"
                                                       required>
                                                @error('detalles.{{ $index }}.precio_venta')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <textarea name="detalles[{{ $index }}][descripcion]"
                                                          class="form-control descripcion @error('detalles.{{ $index }}.descripcion') is-invalid @enderror"
                                                          placeholder="Descripción (opcional)"
                                                          maxlength="500">{{ $detalle['descripcion'] ?? '' }}</textarea>
                                                @error('detalles.{{ $index }}.descripcion')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm remove-detalle"
                                                        {{ $orden->estado !== 'procesando' ? 'disabled' : '' }}>
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mb-3">
                            <label for="estado" class="form-label"><strong>Actualizar estado:</strong></label>
                            <select name="estado" id="estado" class="form-select w-auto d-inline-block">
                                <option value="procesando" {{ $orden->estado === 'procesando' ? 'selected' : '' }}>Procesando</option>
                                <option value="entregado" {{ $orden->estado === 'entregado' ? 'selected' : '' }}>Entregado</option>
                                <option value="cancelado" {{ $orden->estado === 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div>
                                <button type="button" class="btn btn-success" id="add-detalle"
                                        {{ $orden->estado !== 'procesando' ? 'disabled' : '' }}>
                                    <i class="bi bi-plus-circle me-2"></i>Agregar Detalle
                                </button>
                                <a href="{{ route('admin.proveedores.ordenes.historial', $proveedor) }}"
                                   class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>Volver al historial
                                </a>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-2"></i>Guardar Cambios
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="row gy-4 align-items-start">
                <div class="col-12 col-md-4 text-center text-md-start mb-4 mb-md-0">
                    <h5 class="mb-3 text-uppercase fw-bold">Síguenos</h5>
                    <div class="d-flex justify-content-center justify-content-md-start gap-3">
                        <a href="https://wa.me/573172343575?text=¡Hola! Estoy interesado en obtener más información sobre el software D'Jenny para tiendas de abarrotes." target="_blank" title="Contactar por WhatsApp">
                            <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/whatsapp.svg" alt="WhatsApp" width="28" height="28" style="filter: invert(1);">
                        </a>
                        <a href="https://www.facebook.com/profile.php?id=61560973980821" target="_blank" class="d-inline-block" title="Facebook">
                            <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/facebook.svg" alt="Facebook" width="28" height="28" style="filter: invert(1);">
                        </a>
                        <a href="https://www.instagram.com/alphasoft.5/" target="_blank" class="d-inline-block" title="Instagram">
                            <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/instagram.svg" alt="Instagram" width="28" height="28" style="filter: invert(1);">
                        </a>
                    </div>
                </div>
                <div class="col-12 col-md-4 text-center">
                    <div class="d-grid gap-2 mx-auto" style="max-width: 200px;">
                    </div>
                </div>
                <div class="col-12 col-md-4 text-center text-md-end">
                    <h5 class="mb-3 text-uppercase fw-bold">Encuéntranos</h5>
                    <a href="https://www.google.com/maps?q=Carrera+36A+%232e-57+Neiva-Huila"
                       target="_blank"
                       class="text-white text-decoration-underline"
                       style="font-weight: 500;">
                        Carrera 36A #2e-57
                        <div>Neiva - Huila</div>
                    </a>
                </div>
            </div>
            <hr class="my-4 border-light opacity-25">
            <div class="text-center small">
                <p>© {{ date('Y') }} Tienda D'jenny - Panel de Administración</p>
            </div>
            <div class="faq-float">
                <a href="{{ route('manuala') }}" class="faq-btn">
                    <span class="faq-icon"><i class="bi bi-question-circle"></i></span>
                    <span class="faq-text">Preguntas Frecuentes</span>
                </a>
            </div>
        </div>
    </footer>

    <div class="whatsapp-float">
        <a href="https://wa.me/573172343575?text=¡Hola! Estoy interesado en obtener más información sobre el software D'Jenny para tiendas de abarrotes." target="_blank" title="Contactar por WhatsApp">
            <i class="bi bi-whatsapp" style="font-size: 2rem;"></i>
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleTheme() {
            const body = document.body;
            const currentTheme = body.getAttribute('data-theme');
            body.setAttribute('data-theme', currentTheme === 'dark' ? 'light' : 'dark');
            localStorage.setItem('theme', body.getAttribute('data-theme'));
        }

        document.addEventListener('DOMContentLoaded', () => {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.body.setAttribute('data-theme', savedTheme);

            const container = document.getElementById('detalles-container');
            const addButton = document.getElementById('add-detalle');
            let index = {{ count($orden->detalles) }};

            function updateRemoveButtons() {
                const rows = container.querySelectorAll('.detalle-row');
                rows.forEach(row => {
                    const removeButton = row.querySelector('.remove-detalle');
                    if (removeButton) {
                        removeButton.disabled = rows.length <= 1 || {{ $orden->estado !== 'procesando' ? 'true' : 'false' }};
                    }
                });
            }

            function attachProductChangeListener(row) {
                const productSelect = row.querySelector('.product-select');
                const newProductInput = row.querySelector('.new-product-input');
                const categorySelect = row.querySelector('.categoria-select');
                const stockInput = row.querySelector('input[name$="[stock_actual]"]');
                const newCategoryContainer = row.querySelector('.new-category-container');
                const precioCompraInput = row.querySelector('.precio-compra');
                const porcentajeGananciaSelect = row.querySelector('.porcentaje-ganancia');
                const precioVentaInput = row.querySelector('.precio-venta');
                const descripcionInput = row.querySelector('.descripcion');

                if (productSelect && newProductInput && categorySelect && stockInput) {
                    productSelect.addEventListener('change', function() {
                        const selectedValue = this.value;
                        newProductInput.style.display = selectedValue === 'new' ? 'block' : 'none';
                        newProductInput.value = '';
                        if (selectedValue !== 'new' && selectedValue) {
                            const selectedOption = this.options[this.selectedIndex];
                            categorySelect.value = selectedOption.dataset.categoriaId || '{{ $proveedor->categoria_id ?? '' }}';
                            stockInput.value = selectedOption.dataset.stock || 0;
                        } else {
                            categorySelect.value = '{{ $proveedor->categoria_id ?? '' }}';
                            stockInput.value = 0;
                        }
                        categorySelect.dispatchEvent(new Event('change'));
                    });
                }

                if (categorySelect && newCategoryContainer) {
                    categorySelect.addEventListener('change', function() {
                        newCategoryContainer.style.display = this.value === 'new' ? 'block' : 'none';
                    });
                }

                if (precioCompraInput && porcentajeGananciaSelect && precioVentaInput) {
                    function updatePrecioVenta() {
                        const precioCompra = parseFloat(precioCompraInput.value) || 0;
                        const porcentajeGanancia = parseFloat(porcentajeGananciaSelect.value) || 0;
                        if (precioCompra && porcentajeGanancia) {
                            const precioVenta = precioCompra * (1 + porcentajeGanancia / 100);
                            precioVentaInput.value = precioVenta.toFixed(2);
                        } else {
                            precioVentaInput.value = precioCompraInput.value || 0;
                        }
                    }

                    precioCompraInput.addEventListener('input', updatePrecioVenta);
                    porcentajeGananciaSelect.addEventListener('change', updatePrecioVenta);
                }

                if (descripcionInput) {
                    descripcionInput.addEventListener('input', function() {
                        const value = this.value.trim();
                        if (value) {
                            this.value = value.charAt(0).toUpperCase() + value.slice(1);
                        }
                    });
                }
            }

            if (addButton && container) {
                addButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    if ({{ $orden->estado !== 'procesando' ? 'true' : 'false' }}) return;

                    const newRow = document.createElement('tr');
                    newRow.className = 'detalle-row';
                    newRow.dataset.index = index;
                    newRow.innerHTML = `
                        <td>
                            <select name="detalles[${index}][producto_ids][]" class="form-select product-select" required>
                                <option value="">Seleccione Producto</option>
                                @foreach ($productos as $producto)
                                    <option value="{{ $producto->id }}"
                                            data-categoria-id="{{ $producto->categoria_id }}"
                                            data-stock="{{ $producto->stock }}">
                                        {{ $producto->nombre }}
                                    </option>
                                @endforeach
                                <option value="new">Crear nuevo producto</option>
                            </select>
                            <input type="text" name="detalles[${index}][new_product_name]" class="form-control mt-2 new-product-input"
                                   placeholder="Nombre del nuevo producto" style="display: none;">
                        </td>
                        <td>
                            <select name="detalles[${index}][categoria_id]" class="form-select categoria-select">
                                <option value="">Sin Categoría</option>
                                @foreach ($categorias as $categoria)
                                    <option value="{{ $categoria->id }}"
                                            {{ $proveedor->categoria_id == $categoria->id ? 'selected' : '' }}>
                                        {{ $categoria->nombre }}
                                    </option>
                                @endforeach
                                <option value="new">Crear nueva categoría</option>
                            </select>
                            <div class="new-category-container" id="new-category-container-${index}" style="display: none;">
                                <input type="text" name="detalles[${index}][new_category_name]" class="form-control"
                                       placeholder="Nueva Categoría">
                            </div>
                        </td>
                        <td>
                            <input type="number" name="detalles[${index}][cantidad]" class="form-control"
                                   placeholder="Cantidad" min="1" step="1" value="1" required>
                        </td>
                        <td>
                            <input type="number" name="detalles[${index}][stock_actual]" class="form-control" value="0" readonly>
                        </td>
                        <td>
                            <input type="number" name="detalles[${index}][precio_compra]" class="form-control precio-compra"
                                   step="0.01" min="0" placeholder="Precio Compra" value="0" required>
                        </td>
                        <td>
                            <select name="detalles[${index}][porcentaje_ganancia]"
                                    id="porcentaje_ganancia_${index}"
                                    class="form-select form-select-sm porcentaje-ganancia">
                                <option value="">Selecciona un porcentaje</option>
                                <option value="20">20%</option>
                                <option value="25">25%</option>
                                <option value="30">30%</option>
                                <option value="40">40%</option>
                                <option value="50">50%</option>
                            </select>
                        </td>
                        <td>
                            <input type="number" name="detalles[${index}][precio_venta]" class="form-control precio-venta"
                                   step="0.01" min="0" placeholder="Precio Venta" value="0" required>
                        </td>
                        <td>
                            <textarea name="detalles[${index}][descripcion]" class="form-control descripcion"
                                      placeholder="Descripción (opcional)" maxlength="500"></textarea>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm remove-detalle">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    `;
                    container.appendChild(newRow);
                    attachProductChangeListener(newRow);
                    index++;
                    updateRemoveButtons();
                });
            }

            container.addEventListener('click', function(e) {
                if (e.target.closest('.remove-detalle') && {{ $orden->estado === 'procesando' ? 'true' : 'false' }}) {
                    e.target.closest('.detalle-row').remove();
                    updateRemoveButtons();
                }
            });

            document.querySelectorAll('.detalle-row').forEach(row => {
                attachProductChangeListener(row);
            });

            updateRemoveButtons();

            document.getElementById('orden-form').addEventListener('submit', function(e) {
                const detalles = document.querySelectorAll('.detalle-row');
                let hasErrors = false;
                detalles.forEach(row => {
                    const productSelect = row.querySelector('.product-select');
                    const cantidadInput = row.querySelector('input[name$="[cantidad]"]');
                    const precioCompraInput = row.querySelector('input[name$="[precio_compra]"]');
                    const precioVentaInput = row.querySelector('input[name$="[precio_venta]"]');

                    if (!productSelect.value || productSelect.value === '') {
                        productSelect.classList.add('is-invalid');
                        hasErrors = true;
                    } else {
                        productSelect.classList.remove('is-invalid');
                    }
                    if (!cantidadInput.value || cantidadInput.value < 1) {
                        cantidadInput.classList.add('is-invalid');
                        hasErrors = true;
                    } else {
                        cantidadInput.classList.remove('is-invalid');
                    }
                    if (!precioCompraInput.value || precioCompraInput.value < 0) {
                        precioCompraInput.classList.add('is-invalid');
                        hasErrors = true;
                    } else {
                        precioCompraInput.classList.remove('is-invalid');
                    }
                    if (!precioVentaInput.value || precioVentaInput.value < 0) {
                        precioVentaInput.classList.add('is-invalid');
                        hasErrors = true;
                    } else {
                        precioVentaInput.classList.remove('is-invalid');
                    }
                });
                if (hasErrors) {
                    e.preventDefault();
                    alert('Por favor, complete todos los campos obligatorios.');
                }
            });
        });
    </script>
</body>
</html>