@extends('layouts.app')

@section('title', 'Editar Categoría')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold">Editar Categoría: {{ $categoria->nombre }}</h1>
            <p class="text-muted">Modifica los detalles de la categoría.</p>
        </div>
        <a href="{{ route('categorias.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Volver a Categorías
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <!-- Mostrar errores de validación -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Formulario de edición de categoría -->
            <form method="POST" action="{{ route('categorias.update', $categoria) }}" id="categoryForm">
                @csrf
                @method('PUT')

                <!-- Nombre de la categoría -->
                <div class="mb-4">
                    <label for="nombre" class="form-label fw-semibold">Nombre de la Categoría</label>
                    <input type="text" 
                           class="form-control @error('nombre') is-invalid @enderror" 
                           id="nombre" 
                           name="nombre" 
                           value="{{ old('nombre', $categoria->nombre) }}" 
                           required 
                           maxlength="50"
                           placeholder="Ej: Bebidas">
                    <span id="nombreError" class="invalid-feedback d-block" style="display: none;"></span>
                    @error('nombre')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Descripción de la categoría -->
                <div class="mb-4">
                    <label for="descripcion" class="form-label fw-semibold">Descripción (Opcional)</label>
                    <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                              id="descripcion" 
                              name="descripcion" 
                              rows="3" 
                              placeholder="Describe brevemente esta categoría">{{ old('descripcion', $categoria->descripcion) }}</textarea>
                    @error('descripcion')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Estado de la categoría -->
                <div class="mb-4">
                    <label for="estado" class="form-label fw-semibold">Estado</label>
                    <select name="estado" id="estado" class="form-select @error('estado') is-invalid @enderror" required>
                        <option value="activo" {{ old('estado', $categoria->estado) === 'activo' ? 'selected' : '' }}>Activo</option>
                        <option value="inactivo" {{ old('estado', $categoria->estado) === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                    @error('estado')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Botones -->
                <div class="d-flex justify-content-end gap-3">
                    <button type="submit" class="btn btn-primary" id="submitButton">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                    <a href="{{ route('categorias.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Incluir Font Awesome para íconos (si no está en layouts.app) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<!-- Incluir Axios para validación en tiempo real -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const nombreInput = document.getElementById('nombre');
        const nombreError = document.getElementById('nombreError');
        const submitButton = document.getElementById('submitButton');
        const categoriaId = {{ $categoria->id }};
        let timeout = null;

        nombreInput.addEventListener('input', function () {
            // Clear previous timeout
            clearTimeout(timeout);

            // Debounce: wait 500ms before sending request
            timeout = setTimeout(function () {
                const nombre = nombreInput.value.trim();

                if (nombre.length > 0) {
                    axios.post('{{ route('categorias.checkName') }}', { 
                        nombre: nombre,
                        id: categoriaId
                    })
                        .then(function (response) {
                            if (response.data.exists) {
                                nombreInput.classList.add('is-invalid');
                                nombreError.textContent = response.data.message;
                                nombreError.style.display = 'block';
                                submitButton.disabled = true;
                            } else {
                                nombreInput.classList.remove('is-invalid');
                                nombreError.textContent = '';
                                nombreError.style.display = 'none';
                                submitButton.disabled = false;
                            }
                        })
                        .catch(function (error) {
                            console.error('Error checking name:', error);
                        });
                } else {
                    nombreInput.classList.remove('is-invalid');
                    nombreError.textContent = '';
                    nombreError.style.display = 'none';
                    submitButton.disabled = false;
                }
            }, 500);
        });
    });
</script>
@endsection