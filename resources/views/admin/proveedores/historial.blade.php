<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Historial de Órdenes - {{ $proveedor->nombre }}</title>
    <link rel="icon" href="{{ asset('images/djenny.png') }}" type="image/x-icon">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <style>
        .card { border-radius: 8px; transition: transform 0.2s ease-in-out; }
        .card:hover { transform: translateY(-2px); }
        .badge { font-size: 0.9em; padding: 0.5em 1em; }
        .table th, .table td { vertical-align: middle; }
        .form-control, .form-select { border-radius: 6px; font-size: 0.875rem; padding: 0.25rem 0.5rem; max-width: 200px; }
        .form-control:focus, .form-select:focus { box-shadow: 0 0 4px rgba(0, 123, 255, 0.3); }
        .btn-sm { padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.875rem; transition: transform 0.2s ease-in-out; }
        .btn-sm:hover { transform: scale(1.03); }
        .alert { border-radius: 8px; }
        .bg-info { background-color: #74b9ff; }
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
            <div class="row mb-4 my-3 align-items-center">
                <div class="col-md-6">
                    <h1 class="fw-bold mb-0">Historial de Órdenes - {{ $proveedor->nombre }}</h1>
                    <p>Administra las órdenes de compra de este proveedor</p>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('proveedores.index') }}" class="btn btn-outline-secondary btn-sm me-2">
                        <i class="bi bi-arrow-left me-1"></i>Volver a Proveedores
                    </a>
                    <a href="{{ route('admin.proveedores.ordenes.create', ['proveedor' => $proveedor->id]) }}"
                       class="btn btn-success btn-sm">
                        <i class="bi bi-plus-lg me-1"></i>Nueva Orden
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

            <!-- Filtros y búsqueda -->
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('admin.proveedores.ordenes.historial', $proveedor) }}" method="GET" class="row g-3">
                        <div class="col-md-4">
                            <input type="text" 
                                   name="search" 
                                   class="form-control search-input" 
                                   placeholder="Buscar por #orden o proveedor..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="estado" class="form-select">
                                <option value="">Todos los estados</option>
                                <option value="procesando" {{ request('estado') == 'procesando' ? 'selected' : '' }}>Procesando</option>
                                <option value="entregado" {{ request('estado') == 'entregado' ? 'selected' : '' }}>Entregado</option>
                                <option value="cancelado" {{ request('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-sm w-100 search-btn">
                                <i class="bi bi-search me-1"></i>Filtrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Depuración: Mostrar número de órdenes -->
            <div class="mb-2">
                Mostrando {{ $ordenes->count() }} de {{ $ordenes->total() }} órdenes ({{ $ordenes->perPage() }} por página)
            </div>

            <!-- Lista de órdenes -->
            @if ($ordenes->isEmpty())
                <div class="alert alert-warning text-center shadow-sm">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>No hay órdenes de compra disponibles con los filtros aplicados.</strong>
                </div>
            @else
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th># Orden</th>
                                        <th>Fecha</th>
                                        <th>Productos</th>
                                        <th>Cantidades</th>
                                        <th>Monto Total (COP)</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ordenes as $orden)
                                        <tr>
                                            <td>{{ $orden->id }}</td>
                                            <td>{{ $orden->fecha ? $orden->fecha->format('d/m/Y H:i') : 'Sin especificar' }}</td>
                                            <td>
                                                @foreach ($orden->detalles as $detalle)
                                                    {{ $detalle['producto'] ?? 'Sin nombre' }}<br>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($orden->detalles as $detalle)
                                                    {{ $detalle['cantidad'] ?? 0 }}<br>
                                                @endforeach
                                            </td>
                                            <td>{{ number_format($orden->monto ?? 0, 2, ',', '.') }}</td>
                                            <td>
                                                <span class="badge {{ $orden->estado === 'procesando' ? 'bg-info' : ($orden->estado === 'entregado' ? 'bg-success' : 'bg-secondary') }}">
                                                    {{ ucfirst($orden->estado) }}
                                                    @if ($orden->estado === 'procesando')
                                                        <i class="bi bi-clock ms-1" title="En espera"></i>
                                                    @endif
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.proveedores.ordenes.show', [$proveedor, $orden]) }}"
                                                   class="btn btn-info btn-sm" title="Ver detalles">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                {{-- <a href="{{ route('admin.proveedores.ordenes.edit', [$proveedor, $orden]) }}"
                                                   class="btn btn-warning btn-sm" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </a> --}}
                                                <form action="{{ route('admin.proveedores.ordenes.destroy', [$proveedor, $orden]) }}"
                                                      method="POST" class="d-inline"
                                                      onsubmit="return confirm('¿Estás seguro de eliminar la orden #{{ $orden->id }}?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mt-4">
                            {{ $ordenes->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            @endif
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
        });
    </script>
</body>
</html>