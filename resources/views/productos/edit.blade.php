@extends('layouts.app')

@section('content')
<div class="container py-3">
    <!-- Encabezado -->
    <div class="row mb-3 align-items-center">
        <div class="col-md-6">
            <h2 class="text-primary fw-bold mb-0">Editar Producto</h2>
            <p class="text-muted small">Modifica los detalles del producto</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Volver a Productos
            </a>
        </div>
    </div>

    <!-- Alertas -->
    @if ($errors->any())
        <div class="alert alert-danger alert-sm shadow-sm mb-3" role="alert">
            <i class="bi bi-exclamation-triangle me-1"></i>
            <strong>¡Ups!</strong> Corrige los siguientes errores:
            <ul class="mb-0 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success alert-sm shadow-sm mb-3" role="alert">
            <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
        </div>
    @endif

    <!-- Formulario -->
    <div class="card shadow-lg border-0 p-4">
        <div class="row g-4">
            <!-- Vista previa de imagen y código de barras -->
            <div class="col-md-5">
                <div class="text-center">
                    <img id="preview" 
                         src="{{ $producto->imagen ? asset('storage/'.$producto->imagen) : asset('images/placeholder.png') }}" 
                         class="img-fluid rounded shadow-sm mb-3" 
                         alt="Vista previa" 
                         style="max-height: 200px; object-fit: cover;">
                    <p class="text-muted small" id="preview-text">
                        {{ $producto->imagen ? 'Imagen actual' : 'Sin imagen' }}
                    </p>
                    <!-- Código de barras -->
                    <div class="mt-3">
                        <label class="form-label fw-medium small">Código de Barras</label>
                        <p class="text-muted small mb-2">{{ $producto->codigo_barra ?? 'No asignado' }}</p>
                        @if ($producto->codigo_barra)
                            <img src="{{ route('productos.generar-barcode', ['codigo_barra' => $producto->codigo_barra]) }}" 
                                 alt="Código de barras" 
                                 class="img-fluid" 
                                 style="max-width: 150px; height: auto;">
                        @endif
                    </div>
                </div>
            </div>

            <!-- Campos del formulario -->
            <div class="col-md-7">
                <form action="{{ route('productos.update', $producto->id) }}" method="POST" enctype="multipart/form-data" id="editProductoForm">
                    @csrf
                    @method('PUT')

                    <!-- Nombre -->
                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-medium small">Nombre del Producto</label>
                        <input type="text" 
                               name="nombre" 
                               id="nombre" 
                               class="form-control form-control-sm @error('nombre') is-invalid @enderror" 
                               value="{{ old('nombre', $producto->nombre) }}" 
                               required 
                               placeholder="Ej: Leche Entera">
                        @error('nombre')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Descripción -->
                    <div class="mb-3">
                        <label for="descripcion" class="form-label fw-medium small">Descripción</label>
                        <textarea name="descripcion" 
                                  id="descripcion" 
                                  class="form-control form-control-sm @error('descripcion') is-invalid @enderror" 
                                  rows="3" 
                                  required 
                                  placeholder="Describe el producto...">{{ old('descripcion', $producto->descripcion) }}</textarea>
                        @error('descripcion')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Precio -->
                    <div class="mb-3">
                        <label for="precio" class="form-label fw-medium small">Precio ($)</label>
                        <input type="number" 
                               name="precio" 
                               id="precio" 
                               class="form-control form-control-sm @error('precio') is-invalid @enderror" 
                               step="0.01" 
                               value="{{ old('precio', $producto->precio) }}" 
                               required 
                               placeholder="Ej: 12.34">
                        @error('precio')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Precio Compra -->
                    <div class="mb-3">
                        <label for="precio_compra" class="form-label fw-medium small">Precio Compra ($)</label>
                        <input type="number" 
                               name="precio_compra" 
                               id="precio_compra" 
                               class="form-control form-control-sm @error('precio_compra') is-invalid @enderror" 
                               step="0.01" 
                               value="{{ old('precio_compra', $producto->precio_compra) }}" 
                               placeholder="Ej: 10.00">
                        @error('precio_compra')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Stock -->
                    <div class="mb-3">
                        <label for="stock" class="form-label fw-medium small">Stock</label>
                        <input type="number" 
                               name="stock" 
                               id="stock" 
                               class="form-control form-control-sm @error('stock') is-invalid @enderror" 
                               value="{{ old('stock', $producto->stock) }}" 
                               required 
                               placeholder="Ej: 100">
                        @error('stock')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Estado -->
                    <div class="mb-3">
                        <label for="estado" class="form-label fw-medium small">Estado</label>
                        <select name="estado" 
                                id="estado" 
                                class="form-select form-select-sm @error('estado') is-invalid @enderror" 
                                required>
                            <option value="activo" {{ old('estado', $producto->estado) == 'activo' ? 'selected' : '' }}>Activo</option>
                            <option value="inactivo" {{ old('estado', $producto->estado) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                        @error('estado')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Categoría -->
                    <div class="mb-3">
                        <label for="categoria_id" class="form-label fw-medium small">Categoría</label>
                        <select name="categoria_id" 
                                id="categoria_id" 
                                class="form-select form-select-sm @error('categoria_id') is-invalid @enderror" 
                                required>
                            <option value="">Selecciona una categoría</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}" {{ old('categoria_id', $producto->categoria_id) == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('categoria_id')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Imagen -->
                    <div class="mb-3">
                        <label for="imagen" class="form-label fw-medium small">Imagen del Producto</label>
                        <input type="file" 
                               name="imagen" 
                               id="imagen" 
                               class="form-control form-control-sm @error('imagen') is-invalid @enderror" 
                               accept="image/*" 
                               onchange="mostrarVistaPrevia(event)">
                        <small class="text-muted" style="font-size: 0.75rem;">Formatos: JPEG, PNG, JPG, GIF. Máx 2MB. Deja vacío para mantener la imagen actual.</small>
                        @error('imagen')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Botones -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary btn-sm btn-cancel">
                            <i class="bi bi-x-lg me-1"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-success btn-sm btn-save">
                            <i class="bi bi-save me-1"></i>Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 12px;
        transition: transform 0.2s ease-in-out;
    }

    .card:hover {
        transform: translateY(-2px);
    }

    .form-control-sm, .form-select-sm {
        border-radius: 8px;
        font-size: 0.875rem;
        padding: 0.25rem 0.5rem;
        transition: box-shadow 0.2s ease-in-out;
    }

    .form-control-sm:focus, .form-select-sm:focus {
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }

    .btn-sm {
        padding: 0.5rem 1.5rem;
        border-radius: 20px;
        transition: transform 0.2s ease-in-out;
    }

    .btn-sm:hover {
        transform: scale(1.05);
    }

    .img-fluid {
        border-radius: 8px;
        max-width: 100%;
    }

    .alert-sm {
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
    }

    .alert-sm ul {
        padding-left: 1rem;
    }
</style>

<script>
    function mostrarVistaPrevia(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function() {
                const preview = document.getElementById('preview');
                const previewText = document.getElementById('preview-text');
                preview.src = reader.result;
                previewText.textContent = 'Nueva imagen seleccionada';
                previewText.classList.remove('text-muted');
                previewText.classList.add('text-primary');
            };
            reader.readAsDataURL(file);
        }
    }

    document.querySelector('form').addEventListener('submit', function(e) {
        const nombre = document.getElementById('nombre').value;
        if (!confirm(`¿Estás seguro de guardar los cambios para "${nombre}"?`)) {
            e.preventDefault();
        }
    });
</script>
@endsection