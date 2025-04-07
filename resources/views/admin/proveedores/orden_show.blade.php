@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h1 class="h4 mb-0">Orden de Compra #{{ $orden->id }}</h1>
            </div>
            <div class="card-body">
                <!-- Información general -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <p class="mb-2"><strong class="text-muted">Proveedor:</strong> {{ $proveedor->nombre }}</p>
                        <p class="mb-2"><strong class="text-muted">Fecha:</strong> {{ $orden->fecha->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-2">
                            <strong class="text-muted">Estado:</strong> 
                            <span class="badge {{ $orden->estado === 'pendiente' ? 'bg-warning' : ($orden->estado === 'entregado' ? 'bg-success' : 'bg-secondary') }}">
                                {{ ucfirst($orden->estado) }}
                            </span>
                        </p>
                    </div>
                </div>

                <!-- Detalles de productos -->
                <h3 class="h5 border-bottom pb-2 mb-3">Detalles de la Orden</h3>
                @if (!empty($orden->detalles) && is_array($orden->detalles))
                    <form method="POST" action="{{ route('admin.proveedores.ordenes.update', [$proveedor, $orden]) }}">
                        @csrf
                        @method('PUT')
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Precio Unitario (COP)</th>
                                        <th>Descripción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orden->detalles as $index => $detalle)
                                        <tr>
                                            <td>
                                                <!-- Texto plano del nombre del producto -->
                                                <div>{{ $detalle['producto'] ?? 'No especificado' }}</div>
                                                <!-- Select con lista de productos disponibles -->
                                                <select name="detalles[{{ $index }}][producto]" 
                                                        class="form-select mt-2 @error('detalles.' . $index . '.producto') is-invalid @enderror"
                                                        {{ $orden->estado !== 'pendiente' ? 'disabled' : '' }}>
                                                    <option value="{{ $detalle['producto'] ?? '' }}" selected>
                                                        {{ $detalle['producto'] ?? 'No especificado' }}
                                                    </option>
                                                    @foreach ($productos as $producto)
                                                        @if ($producto->nombre !== ($detalle['producto'] ?? ''))
                                                            <option value="{{ $producto->nombre }}">{{ $producto->nombre }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                @error('detalles.' . $index . '.producto')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>{{ $detalle['cantidad'] ?? 0 }}</td>
                                            <td>
                                                <input type="number" 
                                                       name="detalles[{{ $index }}][precio]" 
                                                       value="{{ $detalle['precio'] ?? '' }}" 
                                                       class="form-control @error('detalles.' . $index . '.precio') is-invalid @enderror" 
                                                       step="0.01" 
                                                       min="0" 
                                                       placeholder="Ingrese precio en COP"
                                                       {{ $orden->estado !== 'pendiente' ? 'disabled' : '' }}>
                                                @error('detalles.' . $index . '.precio')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>{{ $detalle['descripcion'] ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Selección de estado y botones -->
                        @if ($orden->estado === 'pendiente')
                            <div class="mb-3">
                                <label for="estado" class="form-label"><strong>Actualizar estado:</strong></label>
                                <select name="estado" id="estado" class="form-select w-auto d-inline-block">
                                    <option value="entregado">Entregado</option>
                                    <option value="completado">Completado</option>
                                    <option value="cancelado">Cancelado</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        @endif
                    </form>
                @else
                    <div class="alert alert-info" role="alert">
                        No se han especificado detalles para esta orden.
                    </div>
                @endif

                <!-- Botón de acción -->
                <div class="mt-4">
                    <a href="{{ route('admin.proveedores.ordenes.historial', $proveedor) }}" 
                       class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver al historial
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .card {
            border-radius: 10px;
        }
        .badge {
            font-size: 0.9em;
            padding: 0.5em 1em;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .form-control {
            max-width: 150px;
        }
        .form-select {
            max-width: 200px; /* Ajustar el ancho del select si es necesario */
        }
    </style>
@endsection