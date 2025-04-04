@extends('layouts.app')

@section('title', 'Registrar Proveedor')

@section('content')
<div class="container py-4">
    <h2 class="text-primary fw-bold mb-4">Registrar Nuevo Proveedor</h2>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('proveedores.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre del Proveedor</label>
                    <input type="text" class="form-control" id="nombre" name="nombre">
                </div>
                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text" class="form-control" id="telefono" name="telefono">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email">
                </div>
                <div class="mb-3">
                    <label for="direccion" class="form-label">Dirección</label>
                    <textarea class="form-control" id="direccion" name="direccion"></textarea>
                </div>
                <div class="mb-3">
                    <label for="productos_suministrados" class="form-label">Productos Suministrados</label>
                    <input type="text" class="form-control" id="productos_suministrados" name="productos_suministrados[]" placeholder="Ej: Leche, Pan">
                    <small class="text-muted">Separa los productos por comas</small>
                </div>
                <div class="mb-3">
                    <label for="condiciones_pago" class="form-label">Condiciones de Pago</label>
                    <textarea class="form-control" id="condiciones_pago" name="condiciones_pago"></textarea>
                </div>
                <div class="mb-3">
                    <label for="fecha_vencimiento_contrato" class="form-label">Fecha de Vencimiento del Contrato</label>
                    <input type="datetime-local" class="form-control" id="fecha_vencimiento_contrato" name="fecha_vencimiento_contrato">
                </div>
                <button type="submit" class="btn btn-primary">Registrar</button>
                <a href="{{ route('proveedores.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
@endsection