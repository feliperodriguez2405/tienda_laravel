<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nueva Orden de Compra - {{ $proveedor->nombre }}</title>
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
        .new-product-input { display: none; }
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
                        <button type="button" class="btn btn-sm dropdown-toggle dropdown-toggle-split p-1" data-bs-toggle="dropdown" aria-expanded="false" style="color: white; margin-left:-2px;">
                            <span class="visually-hidden">Toggle Dropdown</span>
                        </button>
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
            <div class="row mb-4 align-items-center">
                <div class="col-md-6">
                    <h2 class="mb-0 fw-bold">Nueva Orden de Compra - {{ $proveedor->nombre }}</h2>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('admin.proveedores.ordenes.historial', ['proveedor' => $proveedor->id]) }}" class="btn btn-outline-secondary">
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
                    <form method="POST" action="{{ route('admin.proveedores.ordenes.store', ['proveedor' => $proveedor->id]) }}" id="orden-form">
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
                            <label class="form-label">Productos <span class="text-danger">*</span></label>
                            <div class="table-responsive">
                                <table class="table table-hover" id="detalles-table">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Producto</th>
                                            <th>Categoría</th>
                                            <th>Cantidad</th>
                                            <th>Stock Actual</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody id="detalles-container">
                                        <tr class="detalle-row" data-index="0">
                                            <td>
                                                <select name="detalles[0][producto_ids][]" class="form-select product-select @error('detalles.0.producto_ids') is-invalid @enderror" required>
                                                    <option value="">Seleccione Producto</option>
                                                    @foreach($productos as $producto)
                                                        <option value="{{ $producto->id }}"
                                                                data-categoria-id="{{ $producto->categoria_id }}"
                                                                data-stock="{{ $producto->stock }}"
                                                                {{ in_array($producto->id, old('detalles.0.producto_ids', [])) ? 'selected' : '' }}>
                                                            {{ $producto->nombre }} ({{ $producto->categoria ? $producto->categoria->nombre : 'Sin categoría' }})
                                                        </option>
                                                    @endforeach
                                                    <option value="new">Crear nuevo producto</option>
                                                </select>
                                                @error('detalles.0.producto_ids')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <input type="text" name="detalles[0][new_product_name]" class="form-control mt-2 new-product-input @error('detalles.0.new_product_name') is-invalid @enderror"
                                                       placeholder="Nombre del nuevo producto" style="display: {{ in_array('new', old('detalles.0.producto_ids', [])) ? 'block' : 'none' }};"
                                                       value="{{ old('detalles.0.new_product_name') }}">
                                                @error('detalles.0.new_product_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <select name="detalles[0][categoria_id]" class="form-select categoria-select @error('detalles.0.categoria_id') is-invalid @enderror" required>
                                                    <option value="">Seleccione Categoría</option>
                                                    @foreach($categorias as $categoria)
                                                        <option value="{{ $categoria->id }}"
                                                                {{ old('detalles.0.categoria_id', $proveedor->categoria_id) == $categoria->id ? 'selected' : '' }}>
                                                            {{ $categoria->nombre }}
                                                        </option>
                                                    @endforeach
                                                    <option value="new">Crear nueva categoría</option>
                                                </select>
                                                @error('detalles.0.categoria_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <div id="new-category-container-0" style="display: {{ old('detalles.0.categoria_id', $proveedor->categoria_id) == 'new' ? 'block' : 'none' }};">
                                                    <input type="text" name="detalles[0][new_category_name]" class="form-control @error('detalles.0.new_category_name') is-invalid @enderror"
                                                           placeholder="Nueva Categoría" value="{{ old('detalles.0.new_category_name') }}">
                                                    @error('detalles.0.new_category_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </td>
                                            <td>
                                                <input type="number" name="detalles[0][cantidad]" class="form-control @error('detalles.0.cantidad') is-invalid @enderror"
                                                       placeholder="Cantidad" value="{{ old('detalles.0.cantidad', 1) }}" min="1" step="1" required>
                                                @error('detalles.0.cantidad')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="number" name="detalles[0][stock_actual]" class="form-control"
                                                       value="{{ old('detalles.0.producto_ids.0') && is_numeric(old('detalles.0.producto_ids.0')) ? ($productos->find(old('detalles.0.producto_ids.0'))?->stock ?? 0) : 0 }}"
                                                       readonly>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm remove-detalle" disabled>
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
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
                    <h5 class="text-uppercase fw-bold mb-3">Manuales</h5>
                    <div class="d-grid gap-2 mx-auto" style="max-width: 200px;">
                        <a href="{{ route('manual') }}" target="_blank" class="btn btn-outline-light btn-sm">
                            <i class="bi bi-file-earmark-text-fill me-1"></i> Manual de Usuario
                        </a>
                        <a href="{{ route('manual') }}" target="_blank" class="btn btn-outline-light btn-sm">
                            <i class="bi bi-file-earmark-text-fill me-1"></i> Manual Técnico
                        </a>
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

            let index = 1;
            const container = document.getElementById('detalles-container');
            const addButton = document.getElementById('add-detalle');

            if (!container || !addButton) {
                console.error('Error: elementos container o addButton no encontrados');
                return;
            }

            function capitalizeFirstLetter(str) {
                return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
            }

            function updateRemoveButtons() {
                const rows = container.querySelectorAll('.detalle-row');
                rows.forEach(row => {
                    const removeButton = row.querySelector('.remove-detalle');
                    if (removeButton) {
                        removeButton.disabled = rows.length <= 1;
                    }
                });
            }

            function attachCategoryChangeListener(row) {
                const select = row.querySelector('.categoria-select');
                const newCategoryContainer = row.querySelector('div[id^="new-category-container-"]');
                if (select && newCategoryContainer) {
                    select.addEventListener('change', function() {
                        newCategoryContainer.style.display = this.value === 'new' ? 'block' : 'none';
                    });
                }
            }

            function attachProductChangeListener(row) {
                const productSelect = row.querySelector('.product-select');
                const newProductInput = row.querySelector('.new-product-input');
                const categorySelect = row.querySelector('.categoria-select');
                const stockInput = row.querySelector('input[name$="[stock_actual]"]');

                if (productSelect && newProductInput && categorySelect && stockInput) {
                    productSelect.addEventListener('change', function() {
                        const selectedValue = this.value;
                        newProductInput.style.display = selectedValue === 'new' ? 'block' : 'none';
                        newProductInput.value = '';
                        if (selectedValue !== 'new' && selectedValue) {
                            const selectedOption = this.options[this.selectedIndex];
                            if (selectedOption && selectedOption.dataset.categoriaId) {
                                categorySelect.value = selectedOption.dataset.categoriaId;
                                stockInput.value = selectedOption.dataset.stock || 0;
                            } else {
                                categorySelect.value = '{{ $proveedor->categoria_id ?? '' }}';
                                stockInput.value = 0;
                            }
                        } else {
                            categorySelect.value = '{{ $proveedor->categoria_id ?? '' }}';
                            stockInput.value = 0;
                        }
                        categorySelect.dispatchEvent(new Event('change'));
                    });

                    newProductInput.addEventListener('input', function() {
                        this.value = capitalizeFirstLetter(this.value);
                    });
                }
            }

            addButton.addEventListener('click', function(e) {
                e.preventDefault();
                const newRow = document.createElement('tr');
                newRow.className = 'detalle-row';
                newRow.dataset.index = index;
                newRow.innerHTML = `
                    <td>
                        <select name="detalles[${index}][producto_ids][]" class="form-select product-select" required>
                            <option value="">Seleccione Producto</option>
                            @foreach($productos as $producto)
                                <option value="{{ $producto->id }}"
                                        data-categoria-id="{{ $producto->categoria_id }}"
                                        data-stock="{{ $producto->stock }}">
                                    {{ $producto->nombre }} ({{ $producto->categoria ? $producto->categoria->nombre : 'Sin categoría' }})
                                </option>
                            @endforeach
                            <option value="new">Crear nuevo producto</option>
                        </select>
                        <input type="text" name="detalles[${index}][new_product_name]" class="form-control mt-2 new-product-input"
                               placeholder="Nombre del nuevo producto" style="display: none;">
                    </td>
                    <td>
                        <select name="detalles[${index}][categoria_id]" class="form-select categoria-select" required>
                            <option value="">Seleccione Categoría</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}"
                                        {{ $proveedor->categoria_id == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->nombre }}
                                </option>
                            @endforeach
                            <option value="new">Crear nueva categoría</option>
                        </select>
                        <div id="new-category-container-${index}" style="display: none;">
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
                        <button type="button" class="btn btn-danger btn-sm remove-detalle">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                `;
                container.appendChild(newRow);
                attachCategoryChangeListener(newRow);
                attachProductChangeListener(newRow);
                index++;
                updateRemoveButtons();
            });

            container.addEventListener('click', function(e) {
                if (e.target.closest('.remove-detalle')) {
                    e.target.closest('.detalle-row').remove();
                    updateRemoveButtons();
                }
            });

            @if(old('detalles'))
                @foreach(old('detalles') as $i => $detalle)
                    @if($i > 0)
                        const newRow = document.createElement('tr');
                        newRow.className = 'detalle-row';
                        newRow.dataset.index = {{ $i }};
                        newRow.innerHTML = `
                            <td>
                                <select name="detalles[${index}][producto_ids][]" class="form-select product-select @error('detalles.{{ $i }}.producto_ids') is-invalid @enderror" required>
                                    <option value="">Seleccione Producto</option>
                                    @foreach($productos as $producto)
                                        <option value="{{ $producto->id }}"
                                                data-categoria-id="{{ $producto->categoria_id }}"
                                                data-stock="{{ $producto->stock }}"
                                                {{ in_array($producto->id, $detalle['producto_ids'] ?? []) ? 'selected' : '' }}>
                                            {{ $producto->nombre }} ({{ $producto->categoria ? $producto->categoria->nombre : 'Sin categoría' }})
                                        </option>
                                    @endforeach
                                    <option value="new">Crear nuevo producto</option>
                                </select>
                                @error('detalles.{{ $i }}.producto_ids')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <input type="text" name="detalles[${index}][new_product_name]" class="form-control mt-2 new-product-input @error('detalles.{{ $i }}.new_product_name') is-invalid @enderror"
                                       placeholder="Nombre del nuevo producto" style="display: {{ in_array('new', $detalle['producto_ids'] ?? []) ? 'block' : 'none' }};"
                                       value="{{ $detalle['new_product_name'] ?? '' }}">
                                @error('detalles.{{ $i }}.new_product_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </td>
                            <td>
                                <select name="detalles[${index}][categoria_id]" class="form-select categoria-select @error('detalles.{{ $i }}.categoria_id') is-invalid @enderror" required>
                                    <option value="">Seleccione Categoría</option>
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->id }}"
                                                {{ ($detalle['categoria_id'] ?? $proveedor->categoria_id) == $categoria->id ? 'selected' : '' }}>
                                            {{ $categoria->nombre }}
                                        </option>
                                    @endforeach
                                    <option value="new">Crear nueva categoría</option>
                                </select>
                                @error('detalles.{{ $i }}.categoria_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="new-category-container-${index}" style="display: {{ ($detalle['categoria_id'] ?? '') == 'new' ? 'block' : 'none' }};">
                                    <input type="text" name="detalles[${index}][new_category_name]" class="form-control @error('detalles.{{ $i }}.new_category_name') is-invalid @enderror"
                                           placeholder="Nueva Categoría" value="{{ $detalle['new_category_name'] ?? '' }}">
                                    @error('detalles.{{ $i }}.new_category_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </td>
                            <td>
                                <input type="number" name="detalles[${index}][cantidad]" class="form-control @error('detalles.{{ $i }}.cantidad') is-invalid @enderror"
                                       placeholder="Cantidad" value="{{ $detalle['cantidad'] ?? 1 }}" min="1" step="1" required>
                                @error('detalles.{{ $i }}.cantidad')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </td>
                            <td>
                                <input type="number" name="detalles[${index}][stock_actual]" class="form-control"
                                       value="{{ ($detalle['producto_ids'][0] ?? '') && is_numeric($detalle['producto_ids'][0]) ? ($productos->find($detalle['producto_ids'][0])?->stock ?? 0) : 0 }}"
                                       readonly>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm remove-detalle">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        `;
                        container.appendChild(newRow);
                        const productSelect = newRow.querySelector('.product-select');
                        productSelect.value = {{ json_encode($detalle['producto_ids'][0] ?? '') }};
                        newRow.querySelector('.categoria-select').value = {{ json_encode($detalle['categoria_id'] ?? $proveedor->categoria_id ?? '') }};
                        newRow.querySelector('input[name$="[stock_actual]"]').value = {{ json_encode(($detalle['producto_ids'][0] ?? '') && is_numeric($detalle['producto_ids'][0]) ? ($productos->find($detalle['producto_ids'][0])?->stock ?? 0) : 0) }};
                        attachCategoryChangeListener(newRow);
                        attachProductChangeListener(newRow);
                        index = Math.max(index, {{ $i }} + 1);
                    @endif
                @endforeach
                updateRemoveButtons();
            @endif

            document.querySelectorAll('.detalle-row').forEach(row => {
                attachCategoryChangeListener(row);
                attachProductChangeListener(row);
            });

            updateRemoveButtons();

            document.getElementById('orden-form').addEventListener('submit', function(e) {
                const detalles = document.querySelectorAll('.detalle-row');
                let hasErrors = false;
                detalles.forEach(row => {
                    const productSelect = row.querySelector('.product-select');
                    const cantidadInput = row.querySelector('input[name$="[cantidad]"]');
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