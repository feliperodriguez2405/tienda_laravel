@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header text-white text-center">
                    <h3><i class="fas fa-edit"></i> Editar Producto</h3>
                </div>
                <div class="card-body">
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

                    <form action="{{ route('productos.update', $producto->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Nombre del Producto --}}
                        <div class="mb-3">
                            <label for="nombre" class="form-label"><strong>Nombre del Producto:</strong></label>
                            <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $producto->nombre) }}" required>
                        </div>

                        {{-- Descripción --}}
                        <div class="mb-3">
                            <label for="descripcion" class="form-label"><strong>Descripción:</strong></label>
                            <textarea name="descripcion" class="form-control" rows="3" required>{{ old('descripcion', $producto->descripcion) }}</textarea>
                        </div>

                        {{-- Precio --}}
                        <div class="mb-3">
                            <label for="precio" class="form-label"><strong>Precio:</strong></label>
                            <input type="number" name="precio" class="form-control" step="0.01" value="{{ old('precio', $producto->precio) }}" required>
                        </div>

                        {{-- Stock --}}
                        <div class="mb-3">
                            <label for="stock" class="form-label"><strong>Stock:</strong></label>
                            <input type="number" name="stock" class="form-control" value="{{ old('stock', $producto->stock) }}" required>
                        </div>

                        {{-- Categoría --}}
                        <div class="mb-3">
                            <label for="categoria_id" class="form-label"><strong>Categoría:</strong></label>
                            <select name="categoria_id" class="form-control">
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}" {{ $producto->categoria_id == $categoria->id ? 'selected' : '' }}>
                                        {{ $categoria->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Imagen --}}
                        <div class="mb-3">
                            <label for="imagen" class="form-label"><strong>Imagen del Producto:</strong></label>
                            <input type="file" name="imagen" class="form-control">
                            <small class="text-muted">Deja este campo vacío si no deseas cambiar la imagen.</small>

                            {{-- Previsualización de la imagen actual --}}
                            @if($producto->imagen)
                                <div class="mt-3">
                                    <img src="{{ asset('storage/'.$producto->imagen) }}" alt="Imagen actual" class="img-fluid rounded shadow-sm" style="max-width: 150px;">
                                </div>
                            @endif
                        </div>

                        {{-- Botones --}}
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('productos.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                            <button type="submit">
                                <i class="fas fa-save"></i> Guardar Cambios
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
