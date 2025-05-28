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
                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}">
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text" class="form-control @error('telefono') is-invalid @enderror" id="telefono" name="telefono" value="{{ old('telefono') }}">
                    @error('telefono')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="direccion" class="form-label">Dirección</label>
                    <textarea class="form-control @error('direccion') is-invalid @enderror" id="direccion" name="direccion">{{ old('direccion') }}</textarea>
                    @error('direccion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="productos_suministrados" class="form-label">Productos Suministrados</label>
                    <input type="text" class="form-control @error('productos_suministrados') is-invalid @enderror" id="productos_suministrados" name="productos_suministrados" value="{{ old('productos_suministrados') }}" placeholder="Ej: Leche, Pan">
                    <small class="text-muted">Separa los productos por comas</small>
                    @error('productos_suministrados')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="condiciones_pago" class="form-label">Condiciones de Pago</label>
                    <textarea class="form-control @error('condiciones_pago') is-invalid @enderror" id="condiciones_pago" name="condiciones_pago">{{ old('condiciones_pago') }}</textarea>
                    @error('condiciones_pago')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="fecha_vencimiento_contrato" class="form-label">Fecha de Vencimiento del Contrato</label>
                    <input type="datetime-local" class="form-control @error('fecha_vencimiento_contrato') is-invalid @enderror" id="fecha_vencimiento_contrato" name="fecha_vencimiento_contrato" value="{{ old('fecha_vencimiento_contrato') }}">
                    @error('fecha_vencimiento_contrato')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="recibir_notificaciones" name="recibir_notificaciones" value="1" {{ old('recibir_notificaciones') ? 'checked' : '' }}>
                    <label for="recibir_notificaciones" class="form-check-label">Recibir notificaciones por correo</label>
                </div>
                <div class="mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select class="form-select @error('estado') is-invalid @enderror" id="estado" name="estado">
                        <option value="activo" {{ old('estado', 'activo') == 'activo' ? 'selected' : '' }}>Activo</option>
                        <option value="inactivo" {{ old('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                    @error('estado')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Registrar</button>
                <a href="{{ route('proveedores.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
@endsection