@extends('layouts.app')

@section('title', 'Factura de Pedido #' . $orden->id)

@section('content')
<div class="container py-4">
    <h1 class="text-primary fw-bold">Factura de Pedido #{{ $orden->id }}</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Cliente: {{ $orden->user->name }}</h5>
                    <p>Email: {{ $orden->user->email }}</p>
                    <p>Dirección: {{ $orden->direccion ?? 'No especificada' }}</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p>Fecha: {{ $orden->created_at->format('d/m/Y H:i') }}</p>
                    <p>Estado: {{ ucfirst($orden->estado) }}</p>
                </div>
            </div>

            <h5>Detalles del Pedido</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orden->detalles as $detalle)
                        <tr>
                            <td>{{ $detalle->producto->nombre }}</td>
                            <td>{{ $detalle->cantidad }}</td>
                            <td>${{ number_format($detalle->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="text-end fw-bold">Total:</td>
                        <td>${{ number_format($orden->total, 2) }}</td>
                    </tr>
                </tfoot>
            </table>

            <a href="{{ route('admin.pedidos') }}" class="btn btn-primary">Volver a Pedidos</a>
            <button onclick="window.print()" class="btn btn-secondary">Imprimir Factura</button>
        </div>
    </div>
</div>
@endsection