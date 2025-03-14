@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Agregar Nueva Categoría</h1>

    {{-- Mostrar errores de validación --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulario de creación de categoría --}}
    <form method="POST" action="{{ route('categorias.store') }}">
        @csrf
        <div class="form-group">
            <label for="nombre">Nombre de la Categoría:</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Agregar Categoría</button>
    </form>
</div>
@endsection
