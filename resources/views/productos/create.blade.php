@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 text-center text-primary fw-bold">Agregar Nuevo Producto</h2>

    {{-- Alertas de errores --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>¡Ups!</strong> Hubo algunos problemas con los datos ingresados.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card p-4 shadow-lg border-0">
        <div class="row">
            <div class="col-md-6">
                {{-- Vista previa de imagen --}}
                <img id="preview" src="{{ asset('images/placeholder.png') }}" class="img-fluid rounded shadow" alt="Vista previa">
            </div>
            <div class="col-md-6">
                <form action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Nombre --}}
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del Producto:</label>
                        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Descripción --}}
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción:</label>
                        <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" rows="3" required>{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Precio --}}
                    <div class="mb-3">
                        <label for="precio" class="form-label">Precio:</label>
                        <input type="number" name="precio" class="form-control @error('precio') is-invalid @enderror" step="0.01" value="{{ old('precio') }}" required>
                        @error('precio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Stock --}}
                    <div class="mb-3">
                        <label for="stock" class="form-label">Stock:</label>
                        <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock') }}" required>
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Categoría --}}
                    <div class="mb-3">
                        <label for="categoria_id" class="form-label">Categoría:</label>
                        <select name="categoria_id" class="form-control @error('categoria_id') is-invalid @enderror">
                            @if(isset($categorias) && $categorias->count() > 0)
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                @endforeach
                            @else
                                <option value="">No hay categorías disponibles</option>
                            @endif
                        </select>
                        @error('categoria_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Imagen --}}
                    <div class="mb-3">
                        <label for="imagen" class="form-label">Imagen del Producto:</label>
                        <input type="file" name="imagen" id="imagen" class="form-control @error('imagen') is-invalid @enderror" accept="image/*" onchange="mostrarVistaPrevia(event)">
                        @error('imagen')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Botones --}}
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Producto</button>
                        <a href="{{ route('productos.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Script para mostrar la vista previa de la imagen --}}
<script>
    function mostrarVistaPrevia(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const preview = document.getElementById('preview');
            preview.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection
