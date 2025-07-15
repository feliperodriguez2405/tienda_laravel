@extends('layouts.app')

@section('content')
<div class="container py-3">
    <!-- Encabezado -->
    <div class="row mb-3 align-items-center">
        <div class="col-md-6">
            <h2 class="text-primary fw-semibold mb-0" style="font-size: 1.5rem;">Agregar Nuevo Producto</h2>
            <p class="text-muted small mb-0">Añade un producto al inventario</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Volver
            </a>
        </div>
    </div>

    <!-- Alertas de errores o éxito -->
    @if ($errors->any())
        <div class="alert alert-danger alert-sm shadow-sm mb-3" role="alert">
            <i class="bi bi-exclamation-triangle me-1"></i>
            <strong>¡Ups!</strong>
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
    <div class="card p-3 shadow-sm border-0">
        <form action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data" id="productoForm">
            @csrf
            <div class="row g-2">
                <!-- Código de barras -->
                <div class="col-md-6 mb-2">
                    <label for="codigo_barra" class="form-label fw-medium small">Código de Barras</label>
                    <div class="input-group input-group-sm">
                        <input type="text" 
                               name="codigo_barra" 
                               id="codigo_barra" 
                               class="form-control form-control-sm @error('codigo_barra') is-invalid @enderror" 
                               value="{{ old('codigo_barra') }}" 
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
                </div>

                <!-- Nombre -->
                <div class="col-md-6 mb-2">
                    <label for="nombre" class="form-label fw-medium small">Nombre *</label>
                    <input type="text" 
                           name="nombre" 
                           id="nombre" 
                           class="form-control form-control-sm @error('nombre') is-invalid @enderror" 
                           value="{{ old('nombre') }}" 
                           required 
                           placeholder="Ej: Leche Entera">
                    @error('nombre')
                        <div class="invalid-feedback small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Precio Compra -->
                <div class="col-md-3 mb-2">
                    <label for="precio_compra" class="form-label fw-medium small">Precio Compra ($)</label>
                    <input type="number" 
                           name="precio_compra" 
                           id="precio_compra" 
                           class="form-control form-control-sm @error('precio_compra') is-invalid @enderror" 
                           step="0.01" 
                           value="{{ old('precio_compra') }}" 
                           placeholder="Ej: 10.00">
                    @error('precio_compra')
                        <div class="invalid-feedback small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Porcentaje de Ganancia -->
                <div class="col-md-3 mb-2">
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
                <div class="col-md-3 mb-2">
                    <label for="precio" class="form-label fw-medium small">Precio Venta ($) *</label>
                    <input type="number" 
                           name="precio" 
                           id="precio" 
                           class="form-control form-control-sm @error('precio') is-invalid @enderror" 
                           step="0.01" 
                           value="{{ old('precio') }}" 
                           required 
                           placeholder="Ej: 12.34">
                    @error('precio')
                        <div class="invalid-feedback small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Stock -->
                <div class="col-md-3 mb-2">
                    <label for="stock" class="form-label fw-medium small">Stock *</label>
                    <input type="number" 
                           name="stock" 
                           id="stock" 
                           class="form-control form-control-sm @error('stock') is-invalid @enderror" 
                           value="{{ old('stock', 0) }}" 
                           required 
                           placeholder="Ej: 100">
                    @error('stock')
                        <div class="invalid-feedback small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Estado -->
                <div class="col-md-3 mb-2">
                    <label for="estado" class="form-label fw-medium small">Estado *</label>
                    <select name="estado" 
                            id="estado" 
                            class="form-select form-select-sm @error('estado') is-invalid @enderror" 
                            required>
                        <option value="activo" {{ old('estado', 'activo') == 'activo' ? 'selected' : '' }}>Activo</option>
                        <option value="inactivo" {{ old('estado', 'activo') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                    @error('estado')
                        <div class="invalid-feedback small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Categoría -->
                <div class="col-md-3 mb-2">
                    <label for="categoria_id" class="form-label fw-medium small">Categoría *</label>
                    <select name="categoria_id" 
                            id="categoria_id" 
                            class="form-select form-select-sm @error('categoria_id') is-invalid @enderror" 
                            required>
                        <option value="">Selecciona una categoría</option>
                        @if(isset($categorias) && $categorias->count() > 0)
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->nombre }}
                                </option>
                            @endforeach
                        @else
                            <option value="" disabled>No hay categorías</option>
                        @endif
                    </select>
                    @error('categoria_id')
                        <div class="invalid-feedback small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Imagen -->
                <div class="col-md-6 mb-2">
                    <label for="imagen" class="form-label fw-medium small">Imagen</label>
                    <input type="file" 
                           name="imagen" 
                           id="imagen" 
                           class="form-control form-control-sm @error('imagen') is-invalid @enderror" 
                           accept="image/jpeg,image/png,image/jpg,image/gif">
                    <small class="text-muted" style="font-size: 0.75rem;">JPEG, PNG, JPG, GIF (Máx 2MB)</small>
                    @error('imagen')
                        <div class="invalid-feedback small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Descripción -->
                <div class="col-12 mb-2">
                    <label for="descripcion" class="form-label fw-medium small">Descripción *</label>
                    <textarea name="descripcion" 
                              id="descripcion" 
                              class="form-control form-control-sm @error('descripcion') is-invalid @enderror" 
                              rows="2" 
                              required 
                              placeholder="Describe el producto...">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <div class="invalid-feedback small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Vista previa del código de barras -->
                <div class="col-12 mb-2" id="barcodePreview" style="display: none;">
                    <label class="form-label fw-medium small">Vista previa del código de barras</label>
                    <div id="barcodeImage"></div>
                </div>

                <!-- Botones -->
                <div class="col-12 d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-save me-1"></i>Guardar
                    </button>
                    <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-x-lg me-1"></i>Cancelar
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .card {
        border-radius: 8px;
        transition: transform 0.2s ease-in-out;
    }

    .card:hover {
        transform: translateY(-1px);
    }

    .form-control-sm, .form-select-sm {
        border-radius: 6px;
        font-size: 0.875rem;
        padding: 0.25rem 0.5rem;
    }

    .form-control-sm:focus, .form-select-sm:focus {
        box-shadow: 0 0 4px rgba(0, 123, 255, 0.3);
    }

    .btn-sm {
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-size: 0.875rem;
        transition: transform 0.2s ease-in-out;
    }

    .btn-sm:hover {
        transform: scale(1.03);
    }

    .alert-sm {
        padding: 0.5rem 0.75rem;
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
        if (!codigo) return;

        $.ajax({
            url: '{{ route("productos.buscar") }}',
            method: 'GET',
            data: { codigo_barra: codigo },
            success: function(response) {
                if (response.success) {
                    // Rellenar los campos con los datos del producto encontrado
                    $('#nombre').val(capitalizeWords(response.producto.nombre));
                    $('#descripcion').val(capitalizeWords(response.producto.descripcion));
                    $('#precio').val(response.producto.precio);
                    $('#precio_compra').val(response.producto.precio_compra);
                    $('#stock').val(response.producto.stock);
                    $('#estado').val(response.producto.estado);
                    $('#categoria_id').val(response.producto.categoria_id);
                    $('#porcentaje_ganancia').val(''); // No hay porcentaje almacenado
                    $('#barcodeImage').html('<img src="data:image/png;base64,' + response.barcode + '" alt="Código de barras">');
                    $('#barcodePreview').show();
                    $('#productoForm').attr('action', '{{ url("productos") }}/' + response.producto.id);
                    $('#productoForm').attr('method', 'PUT');
                    $('#productoForm').append('<input type="hidden" name="_method" value="PUT">');
                } else {
                    // Limpiar el formulario para un nuevo producto
                    $('#productoForm')[0].reset();
                    $('#productoForm').attr('action', '{{ route("productos.store") }}');
                    $('#productoForm').attr('method', 'POST');
                    $('#productoForm').find('input[name="_method"]').remove();
                    $('#barcodePreview').hide();
                    if (codigo) {
                        // Generar vista previa del código de barras
                        $.ajax({
                            url: '{{ route("productos.generar-barcode") }}',
                            method: 'GET',
                            data: { codigo_barra: codigo },
                            success: function(barcodeResponse) {
                                $('#barcodeImage').html('<img src="data:image/png;base64,' + barcodeResponse.barcode + '" alt="Código de barras">');
                                $('#barcodePreview').show();
                            }
                        });
                    }
                }
            },
            error: function() {
                alert('Error al buscar el producto. Por favor, intenta de nuevo.');
            }
        });
    }
});
</script>
@endsection