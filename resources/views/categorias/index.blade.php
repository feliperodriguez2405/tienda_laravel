@extends('layouts.app')

@section('content')
<h1>Lista de Categorías</h1>
<a href="{{ route('categorias.create') }}" class="btn btn-primary">Agregar Categoría</a>
<table class="table">
    <tr>
        <th>Nombre</th>
        <th>Acciones</th>
    </tr>
    @foreach($categorias as $categoria)
    <tr>
        <td>{{ $categoria->nombre }}</td>
        <td>
            <a href="{{ route('categorias.show', $categoria->id) }}" class="btn btn-info">Ver</a>
            <a href="{{ route('categorias.edit', $categoria->id) }}" class="btn btn-warning">Editar</a>
            <form action="{{ route('categorias.destroy', $categoria->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Eliminar</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
@endsection
