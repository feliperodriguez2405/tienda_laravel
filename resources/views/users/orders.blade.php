@extends('layouts.app2')

@section('title', 'Mis Órdenes')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center text-primary fw-bold">Mis Órdenes</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (!$pedidos || $pedidos->isEmpty())
        <div class="alert alert-warning text-center">
            <strong>No tienes órdenes registradas.</strong>
        </div>
    @else
        <div class="row">
            @foreach($pedidos as $pedido)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-dark fw-bold">Orden #{{ $pedido->id }}</h5>
                            <p class="card-text text-success fw-bold">${{ number_format($pedido->total, 2) }}</p>
                            <p class="card-text">Estado: <strong>{{ $pedido->estado }}</strong></p>
                            <p class="card-text">Método de Pago: <strong>{{ $pedido->metodo_pago ?? 'No especificado' }}</strong></p>
                            <p class="card-text">Fecha: <strong>{{ $pedido->created_at->format('d/m/Y') }}</strong></p>
                            <a href="{{ route('user.orders.show', $pedido->id) }}" class="btn btn-info">
                                <i class="bi bi-eye"></i> Ver Detalles
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection