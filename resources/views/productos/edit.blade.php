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
    @if (session('error'))
        <div class="alert alert-danger alert-sm shadow-sm mb-3" role="alert">
            <i class="bi bi-exclamation-circle me-1"></i>{{ session('error') }}
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
                        <div class="input-group input-group-sm">
                            <input type="text" 
                                   name="codigo_barra" 
                                   id="codigo_barra" 
                                   class="form-control form-control-sm @error('codigo_barra') is-invalid @enderror" 
                                   value="{{ old('codigo_barra', $producto->codigo_barra) }}" 
                                   placeholder="Escanea o ingresa el código de barras"
                                   autofocus>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="buscarProducto">
                                <i class="bi bi-search"></i> Buscar
                            </button>
                        </div>
                        <small class="text-muted" style="font-size: 0.75rem;">Escanea con el lector o ingresa manualmente</small>
                        @error('codigo_barra')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                        <div id="barcodePreview" class="mt-2" style="{{ $producto->codigo_barra ? '' : 'display: none;' }}">
                            <img id="barcodeImage" 
                                 src="{{ $producto->codigo_barra ? route('productos.generar-barcode', ['codigo_barra' => $producto->codigo_barra]) : '' }}" 
                                 alt="Código de barras" 
                                 class="img-fluid" 
                                 style="max-width: 150px; height: auto;">
                        </div>
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
                        <label for="nombre" class="form-label fw-medium small">Nombre del Producto *</label>
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
                        <label for="descripcion" class="form-label fw-medium small">Descripción *</label>
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

                    <!-- Porcentaje de Ganancia -->
                    <div class="mb-3">
                        <label for="porcentaje_ganancia" class="form-label fw-medium small">Porcentaje de Ganancia</label>
                        <select name="porcentaje_ganancia" 
                                id="porcentaje_ganancia" 
                                class="form-select form-select-sm @error('porcentaje_ganancia') is-invalid @enderror">
                            <option value="">Selecciona un porcentaje</option>
                            <option value="20" {{ old('porcentaje_ganancia') == '20' ? 'selected' : '' }}>20%</option>
                            <option value="25" {{ old('porcentaje_ganancia') == '25' ? 'selected' : '' }}>25%</option>
                            <option value="30" {{ old('porcentaje_ganancia') == '30' ? 'selected' : '' }}>30%</option>
                            <option value="40" {{ old('porcentaje_ganancia') == '40' ? 'selected' : '' }}>40%</option>
                            <option value="50" {{ old('porcentaje_ganancia') == '50' ? 'selected' : '' }}>50%</option>
                        </select>
                        @error('porcentaje_ganancia')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Precio -->
                    <div class="mb-3">
                        <label for="precio" class="form-label fw-medium small">Precio Venta ($)</label>
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

                    <!-- Stock -->
                    <div class="mb-3">
                        <label for="stock" class="form-label fw-medium small">Stock *</label>
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
                        <label for="estado" class="form-label fw-medium small">Estado *</label>
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
                        <label for="categoria_id" class="form-label fw-medium small">Categoría *</label>
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

    #barcodeImage img {
        max-width: 200px;
        height: auto;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    let lastKeyTime = 0;
    let keyTimeout = 200; // Tiempo en milisegundos para considerar una entrada como escaneada
    let inputBuffer = '';

    // Función para capitalizar la primera letra de cada palabra
    function capitalizeWords(str) {
        return str.replace(/\b\w/g, char => char.toUpperCase());
    }

    // Capitalizar nombre y descripción al escribir
    $('#nombre').on('input', function() {
        let value = $(this).val();
        if (value) {
            $(this).val(capitalizeWords(value));
        }
    });

    $('#descripcion').on('input', function() {
        let value = $(this).val();
        if (value) {
            $(this).val(capitalizeWords(value));
        }
    });

    // Calcular precio de venta basado en porcentaje
    $('#porcentaje_ganancia').on('change', function() {
        let precioCompra = parseFloat($('#precio_compra').val()) || 0;
        let porcentaje = parseFloat($(this).val()) || 0;
        if (precioCompra > 0 && porcentaje > 0) {
            let precioVenta = precioCompra * (1 + porcentaje / 100);
            $('#precio').val(precioVenta.toFixed(2));
        }
    });

    // Actualizar precio de venta al cambiar precio de compra si hay un porcentaje seleccionado
    $('#precio_compra').on('input', function() {
        let porcentaje = parseFloat($('#porcentaje_ganancia').val()) || 0;
        if (porcentaje > 0) {
            let precioCompra = parseFloat($(this).val()) || 0;
            let precioVenta = precioCompra * (1 + porcentaje / 100);
            $('#precio').val(precioVenta.toFixed(2));
        }
    });

    // Limpiar porcentaje si se modifica manualmente el precio de venta
    $('#precio').on('input', function() {
        $('#porcentaje_ganancia').val('');
    });

    // Manejo de código de barras
    $('#codigo_barra').on('input', function(e) {
        let currentTime = new Date().getTime();
        inputBuffer = $(this).val();

        // Detectar si la entrada es rápida (probablemente un escáner)
        if (currentTime - lastKeyTime < keyTimeout) {
            clearTimeout($(this).data('timeout'));
            $(this).data('timeout', setTimeout(function() {
                if (inputBuffer) {
                    buscarProducto(inputBuffer);
                }
            }, keyTimeout));
        } else {
            // Actualizar vista previa del código de barras al escribir manualmente
            if (inputBuffer) {
                actualizarBarcodePreview(inputBuffer);
            } else {
                $('#barcodePreview').hide();
            }
        }
        lastKeyTime = currentTime;
    });

    $('#codigo_barra').on('keypress', function(e) {
        // Si se presiona Enter, buscar el producto
        if (e.which === 13) {
            e.preventDefault();
            buscarProducto($(this).val());
        }
    });

    $('#buscarProducto').on('click', function() {
        buscarProducto($('#codigo_barra').val());
    });

    function buscarProducto(codigo) {
        if (!codigo) {
            $('#barcodePreview').hide();
            return;
        }

        $.ajax({
            url: '{{ route("productos.buscar") }}',
            method: 'GET',
            data: { codigo_barra: codigo },
            success: function(response) {
                if (response.success && response.producto.id !== {{ $producto->id }}) {
                    // Rellenar los campos con los datos del producto encontrado, si no es el producto actual
                    $('#nombre').val(capitalizeWords(response.producto.nombre));
                    $('#descripcion').val(capitalizeWords(response.producto.descripcion));
                    $('#precio').val(response.producto.precio);
                    $('#precio_compra').val(response.producto.precio_compra);
                    $('#stock').val(response.producto.stock);
                    $('#estado').val(response.producto.estado);
                    $('#categoria_id').val(response.producto.categoria_id);
                    $('#porcentaje_ganancia').val(''); // No hay porcentaje almacenado
                    $('#barcodeImage').attr('src', 'data:image/png;base64,' + response.barcode);
                    $('#barcodePreview').show();
                    $('#editProductoForm').attr('action', '{{ url("productos") }}/' + response.producto.id);
                    $('#editProductoForm').find('input[name="_method"]').val('PUT');
                } else {
                    // Actualizar solo la vista previa del código de barras
                    actualizarBarcodePreview(codigo);
                }
            },
            error: function() {
                alert('Error al buscar el producto. Por favor, intenta de nuevo.');
                actualizarBarcodePreview(codigo);
            }
        });
    }

    function actualizarBarcodePreview(codigo) {
        if (codigo) {
            $.ajax({
                url: '{{ route("productos.generar-barcode") }}',
                method: 'GET',
                data: { codigo_barra: codigo },
                success: function(barcodeResponse) {
                    $('#barcodeImage').attr('src', 'data:image/png;base64,' + barcodeResponse.barcode);
                    $('#barcodePreview').show();
                },
                error: function() {
                    $('#barcodePreview').hide();
                }
            });
        } else {
            $('#barcodePreview').hide();
        }
    }

    // Vista previa de la imagen
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

    // Confirmación al enviar el formulario
    document.querySelector('form').addEventListener('submit', function(e) {
        const nombre = document.getElementById('nombre').value;
        if (!confirm(`¿Estás seguro de guardar los cambios para "${nombre}"?`)) {
            e.preventDefault();
        }
    });
});
</script>
@endsection