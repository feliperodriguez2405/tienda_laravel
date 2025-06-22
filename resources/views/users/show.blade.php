@extends('layouts.app2')

@section('title', 'Detalles de la Orden #' . $orden->id)

@section('content')
<div class="container">
    <h1 class="mb-4 text-center fw-bold">Detalles de la Orden #{{ $orden->id }}</h1>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Información de la Orden</h5>
                </div>
                <div class="card-body">
                    <p><strong>Total:</strong> ${{ number_format($orden->total, 2) }}</p>
                    <p><strong>Estado:</strong> {{ $orden->estado }}</p>
                    <p><strong>Método de Pago:</strong> {{ $orden->metodo_pago ?? 'No especificado' }}</p>
                    <p><strong>Fecha:</strong> {{ $orden->created_at->format('d/m/Y H:i') }}</p>

                    <h5 class="mt-4">Productos</h5>
                    <ul class="list-group">
                        @foreach ($orden->detallesOrdenes as $detalle)
                            <li class="list-group-item">
                                {{ $detalle->producto->nombre }} - Cantidad: {{ $detalle->cantidad }} - Subtotal: ${{ number_format($detalle->subtotal, 2) }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="mt-3 text-center">
                <a href="{{ route('user.orders') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Volver a Mis Pedidos
                </a>
            </div>
        </div>
    </div>
</div>
@endsection