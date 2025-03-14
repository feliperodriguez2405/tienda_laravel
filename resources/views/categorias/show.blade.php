@extends('layouts.app')

@section('content')
<h1>Detalles de la Categoría</h1>
<p><strong>Nombre:</strong> {{ $categoria->nombre }}</p>
<a href="{{ route('categorias.index') }}" class="btn btn-secondary">Volver</a>
@endsection