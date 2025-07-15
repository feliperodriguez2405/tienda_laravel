@extends('layouts.app')

@section('title', 'Editar Proveedor')

@section('content')
<div class="container py-4">
    <h1 class="fw-bold mb-4">Editar Proveedor</h1>

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
            <form action="{{ route('proveedores.update', $proveedor) }}" method="POST" id="proveedorForm">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre del Proveedor <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $proveedor->nombre) }}" required>
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text" class="form-control @error('telefono') is-invalid @enderror" id="telefono" name="telefono" value="{{ old('telefono', $proveedor->telefono) }}">
                    @error('telefono')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $proveedor->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="direccion" class="form-label">Dirección</label>
                    <textarea class="form-control @error('direccion') is-invalid @enderror" id="direccion" name="direccion">{{ old('direccion', $proveedor->direccion) }}</textarea>
                    @error('direccion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="productos_suministrados" class="form-label">Productos Suministrados</label>
                    <select multiple class="form-select @error('productos_suministrados') is-invalid @enderror" id="productos_suministrados" name="productos_suministrados[]">
                        @foreach($productos as $producto)
                            <option value="{{ $producto->id }}" {{ in_array($producto->id, old('productos_suministrados', $proveedor_productos ?? [])) ? 'selected' : '' }}>{{ $producto->nombre }}</option>
                        @endforeach
                    </select>
                    <small class="text-muted">Selecciona múltiples productos manteniendo presionado Ctrl (o Cmd en Mac)</small>
                    @error('productos_suministrados')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="categoria_id" class="form-label">Categoría</label>
                    <select class="form-select @error('categoria_id') is-invalid @enderror" id="categoria_id" name="categoria_id">
                        <option value="">Selecciona una categoría</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}" {{ old('categoria_id', $proveedor->categoria_id) == $categoria->id ? 'selected' : '' }}>{{ $categoria->nombre }}</option>
                        @endforeach
                        <option value="new">Crear nueva categoría</option>
                    </select>
                    @error('categoria_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3" id="new_category_container" style="display: none;">
                    <label for="new_category_name" class="form-label">Nombre de la Nueva Categoría <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('new_category_name') is-invalid @enderror" id="new_category_name" name="new_category_name" value="{{ old('new_category_name') }}">
                    @error('new_category_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="condiciones_pago" class="form-label">Condiciones de Pago</label>
                    <textarea class="form-control @error('condiciones_pago') is-invalid @enderror" id="condiciones_pago" name="condiciones_pago">{{ old('condiciones_pago', $proveedor->condiciones_pago) }}</textarea>
                    @error('condiciones_pago')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="fecha_vencimiento_contrato" class="form-label">Fecha de Vencimiento del Contrato</label>
                    <input type="datetime-local" class="form-control @error('fecha_vencimiento_contrato') is-invalid @enderror" id="fecha_vencimiento_contrato" name="fecha_vencimiento_contrato" value="{{ old('fecha_vencimiento_contrato', $proveedor->fecha_vencimiento_contrato ? $proveedor->fecha_vencimiento_contrato->format('Y-m-d\TH:i') : '') }}">
                    @error('fecha_vencimiento_contrato')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="recibir_notificaciones" name="recibir_notificaciones" value="1" {{ old('recibir_notificaciones', $proveedor->recibir_notificaciones) ? 'checked' : '' }}>
                    <label for="recibir_notificaciones" class="form-check-label">Recibir notificaciones por correo</label>
                </div>
                <div class="mb-3">
                    <label for="estado" class="form-label">Estado <span class="text-danger">*</span></label>
                    <select class="form-select @error('estado') is-invalid @enderror" id="estado" name="estado" required>
                        <option value="activo" {{ old('estado', $proveedor->estado) == 'activo' ? 'selected' : '' }}>Activo</option>
                        <option value="inactivo" {{ old('estado', $proveedor->estado) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                    @error('estado')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="{{ route('proveedores.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('categoria_id').addEventListener('change', function() {
        const newCategoryContainer = document.getElementById('new_category_container');
        newCategoryContainer.style.display = this.value === 'new' ? 'block' : 'none';
    });

    function capitalizeFirstLetter(input) {
        if (input.value.length > 0) {
            input.value = input.value.charAt(0).toUpperCase() + input.value.slice(1);
        }
    }

    document.getElementById('new_category_name').addEventListener('input', function() {
        capitalizeFirstLetter(this);
    });

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('proveedorForm');
        const invalidFields = form.querySelectorAll('.is-invalid');
        if (invalidFields.length > 0) {
            invalidFields[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        const categoriaSelect = document.getElementById('categoria_id');
        if (categoriaSelect.value === 'new') {
            document.getElementById('new_category_container').style.display = 'block';
        }
    });
</script>
@endsection