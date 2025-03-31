@extends('layouts.app')

@section('title', 'Editar Proveedor')

@section('content')
<div class="container py-4">
    <h2 class="text-primary fw-bold mb-4">Editar Proveedor</h2>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.proveedores.update', $proveedor) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre del Proveedor</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $proveedor->nombre }}">
                </div>
                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text" class="form-control" id="telefono" name="telefono" value="{{ $proveedor->telefono }}">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ $proveedor->email }}">
                </div>
                <div class="mb-3">
                    <label for="direccion" class="form-label">Dirección</label>
                    <textarea class="form-control" id="direccion" name="direccion">{{ $proveedor->direccion }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="productos_suministrados" class="form-label">Productos Suministrados</label>
                    <input type="text" class="form-control" id="productos_suministrados" name="productos_suministrados[]" value="{{ implode(', ', $proveedor->productos_suministrados) }}">
                    <small class="text-muted">Separa los productos por comas</small>
                </div>
                <div class="mb-3">
                    <label for="condiciones_pago" class="form-label">Condiciones de Pago</label>
                    <textarea class="form-control" id="condiciones_pago" name="condiciones_pago">{{ $proveedor->condiciones_pago }}</textarea>
                </div>
                <div class="mb-3">
    <label for="fecha_vencimiento_contrato" class="form-label">Fecha de Vencimiento del Contrato</label>
    <input type="datetime-local" class="form-control" id="fecha_vencimiento_contrato" name="fecha_vencimiento_contrato" value="{{ $proveedor->fecha_vencimiento_contrato ? $proveedor->fecha_vencimiento_contrato->format('Y-m-d\TH:i') : '' }}">
</div>
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="{{ route('admin.proveedores') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
@endsection